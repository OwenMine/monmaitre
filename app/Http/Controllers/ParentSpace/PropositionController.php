<?php

declare(strict_types=1);

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Proposition;
use App\Notifications\PlacementCreeNotification;
use App\Notifications\PropositionAccepteeNotification;
use App\Notifications\PropositionRefuseeNotification;
use App\Support\AuthHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PropositionController extends Controller
{
    public function index(): View
    {
        $propositions = Proposition::with(['demande.eleve', 'repetiteur.user'])
            ->whereHas('demande', function ($q) {
                $q->where('parent_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('parent.propositions.index', compact('propositions'));
    }

    public function accepter(Proposition $proposition): RedirectResponse
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($proposition->demande, champ: 'parent_id');

        // ⚡ B.3 : Transaction + lock pour éviter la race condition
        $result = DB::transaction(function () use ($proposition) {
            // Recharger la proposition avec lock
            $proposition = Proposition::lockForUpdate()->find($proposition->id);

            if (! $proposition) {
                return ['error' => 'Proposition introuvable.'];
            }

            // Vérifier le statut
            if ($proposition->statut === 'acceptee') {
                return ['error' => 'Cette proposition a déjà été acceptée.'];
            }

            if ($proposition->statut !== 'proposee') {
                return ['error' => 'Cette proposition n\'est plus disponible.'];
            }

            // Charger les relations
            $proposition->load(['demande.eleve', 'demande.parent', 'repetiteur.user']);

            $demande = $proposition->demande;

            // ⚡ B.2 : Vérifier que le répétiteur est toujours valide
            if ($proposition->repetiteur->statut !== 'profil_valide') {
                return ['error' => 'Ce répétiteur n\'est plus disponible. Contactez MON MAÎTRE au 07 19 16 76 88.'];
            }

            if ($proposition->repetiteur->user->status !== 'actif') {
                return ['error' => 'Ce répétiteur n\'est plus disponible. Contactez MON MAÎTRE au 07 19 16 76 88.'];
            }

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
                    : 'Non spécifiée',  // ⚡ B.9
                'adresse'        => $demande->adresse_cours,
                'date_debut'     => $demande->date_debut_souhaitee ?? now(),
                'frequence'      => $demande->seances_semaine . ' séance(s) / semaine',
                'horaires'       => $demande->horaires_disponibles,
                'tarif_convenu'  => null,
                'statut'         => 'confirme',
                'commentaires'   => 'Placement créé automatiquement suite à l\'acceptation du parent.',
            ]);

            // 3. Mettre à jour le statut de la demande
            $demande->update(['statut' => 'placement_confirme']);

            return [
                'success'     => true,
                'placement'   => $placement,
                'proposition' => $proposition,
            ];
        });

        if (isset($result['error'])) {
            return redirect()->route('parent.propositions.index')
                ->with('error', $result['error']);
        }

        $proposition = $result['proposition'];
        $placement   = $result['placement'];

        // 4. Notifications (hors transaction)
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PropositionAccepteeNotification($proposition));
        }

        $proposition->demande->parent->notify(new PlacementCreeNotification($placement));
        $proposition->repetiteur->user->notify(new PlacementCreeNotification($placement));

        return redirect()->route('parent.propositions.index')
            ->with('success', 'Proposition acceptée ! MON MAÎTRE va organiser le placement.');
    }

    public function refuser(Request $request, Proposition $proposition): RedirectResponse
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($proposition->demande, champ: 'parent_id');

        $request->validate([
            'raison_refus' => 'nullable|string|max:500',
        ]);

        $proposition->update([
            'statut'       => 'refusee',
            'date_reponse' => now(),
            'raison_refus' => $request->raison_refus,
        ]);

        // ⚡ B.4 : Vérifier s'il reste des propositions en attente
        $autresPropositionsEnAttente = Proposition::where('demande_id', $proposition->demande_id)
            ->where('id', '!=', $proposition->id)
            ->where('statut', 'proposee')
            ->exists();

        // Ne repasser en recherche QUE s'il n'y a plus rien en attente
        if (! $autresPropositionsEnAttente) {
            $proposition->demande->update(['statut' => 'recherche_repetiteur']);
        }

        // 🔔 NOTIF AUX ADMINS : proposition refusée
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PropositionRefuseeNotification($proposition));
        }

        $message = $autresPropositionsEnAttente
            ? 'Proposition refusée. D\'autres propositions sont en attente.'
            : 'Vous avez demandé une autre proposition. MON MAÎTRE va en rechercher une nouvelle.';

        return redirect()->route('parent.propositions.index')
            ->with('success', $message);
    }
}