<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Placement;
use App\Models\Proposition;
use App\Models\Repetiteur;
use App\Notifications\NouvellePropositionNotification;
use App\Notifications\PlacementCreeNotification;
use App\Notifications\PropositionAccepteeNotification;
use App\Notifications\PropositionValideeAdminNotification;
use App\Services\AuditLogger;
use App\Services\MatchingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MatchingController extends Controller
{
    protected MatchingService $matching;

    public function __construct(MatchingService $matching)
    {
        $this->matching = $matching;
    }

    public function rechercher(Demande $demande)
    {
        $demande->load(['parent', 'eleve']);

        $suggestions = $this->matching->suggerer($demande);

        $propositionsExistantes = Proposition::where('demande_id', $demande->id)
            ->pluck('repetiteur_id')
            ->toArray();

        // Récupérer les propositions avec leur statut
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

    public function resultats(Request $request, Demande $demande)
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

    public function proposer(Request $request, Demande $demande, Repetiteur $repetiteur)
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

        // Notification au parent
        $demande->parent->notify(new NouvellePropositionNotification($proposition));

        // Log audit
        AuditLogger::admin('PROPOSITION_CREEE',
            "Proposition du répétiteur {$repetiteur->user->name} pour la demande #MM-" . str_pad($demande->id, 4, '0', STR_PAD_LEFT),
            [
                'proposition_id' => $proposition->id,
                'demande_id' => $demande->id,
                'repetiteur_id' => $repetiteur->id,
            ]
        );

        return redirect()->route('admin.matching.rechercher', $demande)
            ->with('success', 'Proposition envoyée au parent avec succès.');
    }

    /**
     * ⚡ ADMIN valide la proposition au nom du parent.
     * Utile quand le parent accepte par téléphone mais ne clique pas sur la plateforme.
     */
    public function validerParAdmin(Request $request, Proposition $proposition)
    {
        $request->validate([
            'message_admin' => 'nullable|string|max:1000',
        ]);

        // ⚡ B.3 : Transaction + lock pour éviter la race condition
        $result = \Illuminate\Support\Facades\DB::transaction(function () use ($proposition, $request) {
            
            // Recharger la proposition avec un lock exclusif
            $proposition = Proposition::lockForUpdate()->find($proposition->id);

            if (! $proposition) {
                return ['error' => 'Proposition introuvable.'];
            }

            // Vérifier le statut
            if (! in_array($proposition->statut, ['proposee', 'refusee'])) {
                return ['error' => 'Cette proposition a déjà été traitée.'];
            }

            // Charger les relations
            $proposition->load(['demande.eleve', 'demande.parent', 'repetiteur.user']);

            // ⚡ B.2 : Vérifier que le répétiteur est toujours valide
            if ($proposition->repetiteur->statut !== 'profil_valide') {
                return ['error' => 'Ce répétiteur n\'est plus disponible (statut : ' . $proposition->repetiteur->statutLabel() . ').'];
            }

            if ($proposition->repetiteur->user->status !== 'actif') {
                return ['error' => 'Le compte de ce répétiteur n\'est plus actif.'];
            }

            $demande = $proposition->demande;

            // Vérifier qu'un placement n'existe pas déjà
            if (Placement::where('demande_id', $demande->id)->exists()) {
                return ['error' => 'Un placement existe déjà pour cette demande.'];
            }

            // 1. Mettre à jour la proposition
            $proposition->update([
                'statut'       => 'acceptee',
                'date_reponse' => now(),
            ]);

            // 2. Créer le placement
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

            // 3. Mettre à jour le statut de la demande
            $demande->update(['statut' => 'placement_confirme']);

            return [
                'success'   => true,
                'placement' => $placement,
                'proposition' => $proposition,
            ];
        });

        // Gestion des erreurs
        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $placement   = $result['placement'];
        $proposition = $result['proposition'];

        // 4. Notifications (hors transaction)
        $proposition->demande->parent->notify(
            new PropositionValideeAdminNotification($proposition, $request->message_admin)
        );
        $proposition->repetiteur->user->notify(
            new PlacementCreeNotification($placement)
        );

        // 5. Log audit
        AuditLogger::admin('PROPOSITION_VALIDEE_ADMIN',
            "Validation manuelle de la proposition pour {$proposition->repetiteur->user->name} sur la demande #MM-" 
                . str_pad($proposition->demande->id, 4, '0', STR_PAD_LEFT),
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