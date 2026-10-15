<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Placement;
use App\Models\Proposition;
use App\Models\Repetiteur;
use App\Notifications\NouvellePropositionNotification;
use App\Notifications\PlacementCreeNotification;
use App\Notifications\PropositionValideeAdminNotification;
use App\Services\AuditLogger;
use App\Services\MatchingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MatchingController extends Controller
{
    public function __construct(
        protected MatchingService $matching
    ) {}

    public function rechercher(Demande $demande): View
    {
        $demande->load(['parent', 'eleve']);

        $suggestions = $this->matching->suggerer($demande);

        $propositionsExistantes = Proposition::where('demande_id', $demande->id)
            ->pluck('repetiteur_id')
            ->toArray();

        $propositions = Proposition::where('demande_id', $demande->id)
            ->with(['repetiteur.user'])
            ->get()
            ->keyBy('repetiteur_id');

        return view('admin.matching.rechercher', compact(
            'demande',
            'suggestions',
            'propositionsExistantes',
            'propositions'
        ));
    }

    public function resultats(Request $request, Demande $demande): View
    {
        $demande->load(['parent', 'eleve']);

        $filtres = $request->only(['commune', 'matiere', 'experience_min']);

        $suggestions = $this->matching->suggerer($demande, $filtres);

        $propositionsExistantes = Proposition::where('demande_id', $demande->id)
            ->pluck('repetiteur_id')
            ->toArray();

        $propositions = Proposition::where('demande_id', $demande->id)
            ->with(['repetiteur.user'])
            ->get()
            ->keyBy('repetiteur_id');

        return view('admin.matching.rechercher', compact(
            'demande',
            'suggestions',
            'propositionsExistantes',
            'propositions',
            'filtres'
        ));
    }

    public function proposer(Request $request, Demande $demande, Repetiteur $repetiteur): RedirectResponse
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        $existe = Proposition::where('demande_id', $demande->id)
            ->where('repetiteur_id', $repetiteur->id)
            ->whereIn('statut', ['proposee', 'acceptee'])
            ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Une proposition existe déjà pour ce répétiteur.');
        }

        $proposition = Proposition::create([
            'demande_id'       => $demande->id,
            'repetiteur_id'    => $repetiteur->id,
            'statut'           => 'proposee',
            'message'          => $request->message,
            'date_proposition' => now(),
        ]);

        $demande->update(['statut' => 'attente_confirmation_parent']);

        $demande->parent->notify(new NouvellePropositionNotification($proposition));

        AuditLogger::admin(
            'PROPOSITION_CREEE',
            "Proposition du répétiteur {$repetiteur->user->name} pour la demande #MM-" . str_pad((string) $demande->id, 4, '0', STR_PAD_LEFT),
            [
                'proposition_id' => $proposition->id,
                'demande_id'     => $demande->id,
                'repetiteur_id'  => $repetiteur->id,
            ]
        );

        return redirect()->route('admin.matching.rechercher', $demande)
            ->with('success', 'Proposition envoyée au parent avec succès.');
    }

    /**
     * ADMIN valide la proposition au nom du parent.
     * Utile quand le parent accepte par téléphone mais ne clique pas sur la plateforme.
     */
    public function validerParAdmin(Request $request, Proposition $proposition): RedirectResponse
    {
        $request->validate([
            'message_admin' => 'nullable|string|max:1000',
        ]);

        // ⚡ B.3 : Transaction + lock pour éviter la race condition
        $result = DB::transaction(function () use ($proposition, $request) {
            $proposition = Proposition::lockForUpdate()->find($proposition->id);

            if (! $proposition) {
                return ['error' => 'Proposition introuvable.'];
            }

            if (! in_array($proposition->statut, ['proposee', 'refusee'])) {
                return ['error' => 'Cette proposition a déjà été traitée.'];
            }

            $proposition->load(['demande.eleve', 'demande.parent', 'repetiteur.user']);

            // ⚡ B.2 : Vérifier que le répétiteur est toujours valide
            if ($proposition->repetiteur->statut !== 'profil_valide') {
                return ['error' => 'Ce répétiteur n\'est plus disponible (statut : ' . $proposition->repetiteur->statutLabel() . ').'];
            }

            if ($proposition->repetiteur->user->status !== 'actif') {
                return ['error' => 'Le compte de ce répétiteur n\'est plus actif.'];
            }

            $demande = $proposition->demande;

            if (Placement::where('demande_id', $demande->id)->exists()) {
                return ['error' => 'Un placement existe déjà pour cette demande.'];
            }

            $proposition->update([
                'statut'       => 'acceptee',
                'date_reponse' => now(),
            ]);

            $placement = Placement::create([
                'reference'      => Placement::genererReference(),
                'demande_id'     => $demande->id,
                'proposition_id' => $proposition->id,
                'parent_id'      => $demande->parent_id,
                'repetiteur_id'  => $proposition->repetiteur_id,
                'eleve_id'       => $demande->eleve_id,
                'matiere'        => ! empty($demande->matieres)
                    ? implode(', ', $demande->matieres)
                    : 'Non spécifiée',
                'adresse'        => $demande->adresse_cours,
                'date_debut'     => $demande->date_debut_souhaitee ?? now(),
                'frequence'      => $demande->seances_semaine . ' séance(s) / semaine',
                'horaires'       => $demande->horaires_disponibles,
                'tarif_convenu'  => null,
                'statut'         => 'confirme',
                'commentaires'   => 'Placement créé par l\'admin ' . auth()->user()->name
                    . '. Message : ' . ($request->message_admin ?? 'Aucun'),
            ]);

            $demande->update(['statut' => 'placement_confirme']);

            return [
                'success'     => true,
                'placement'   => $placement,
                'proposition' => $proposition,
            ];
        });

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $placement   = $result['placement'];
        $proposition = $result['proposition'];

        $proposition->demande->parent->notify(
            new PropositionValideeAdminNotification($proposition, $request->message_admin)
        );
        $proposition->repetiteur->user->notify(
            new PlacementCreeNotification($placement)
        );

        AuditLogger::admin(
            'PROPOSITION_VALIDEE_ADMIN',
            "Validation manuelle de la proposition pour {$proposition->repetiteur->user->name} sur la demande #MM-"
                . str_pad((string) $proposition->demande->id, 4, '0', STR_PAD_LEFT),
            [
                'proposition_id' => $proposition->id,
                'placement_id'   => $placement->id,
                'demande_id'     => $proposition->demande->id,
                'message_admin'  => $request->message_admin,
            ]
        );

        return redirect()->route('admin.matching.rechercher', $proposition->demande)
            ->with('success', 'Proposition validée au nom du parent. Le placement a été créé.');
    }
}