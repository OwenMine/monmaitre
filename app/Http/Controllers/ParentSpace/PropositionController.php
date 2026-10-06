<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Proposition;
use App\Notifications\PlacementCreeNotification;
use App\Notifications\PropositionAccepteeNotification;
use App\Notifications\PropositionRefuseeNotification;
use Illuminate\Http\Request;

class PropositionController extends Controller
{
    public function index()
    {
        $propositions = Proposition::with(['demande.eleve', 'repetiteur.user'])
            ->whereHas('demande', function ($q) {
                $q->where('parent_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('parent.propositions.index', compact('propositions'));
    }

    public function accepter(Proposition $proposition)
    {
        if ($proposition->demande->parent_id !== auth()->id()) {
            abort(403);
        }

        if ($proposition->statut === 'acceptee') {
            return redirect()->route('parent.propositions.index')
                ->with('error', 'Cette proposition a déjà été acceptée.');
        }

        $proposition->update([
            'statut'       => 'acceptee',
            'date_reponse' => now(),
        ]);

        $proposition->load(['demande.eleve', 'repetiteur.user']);

        $demande = $proposition->demande;

        // Créer le placement
        $placement = Placement::create([
            'reference'      => Placement::genererReference(),
            'demande_id'     => $demande->id,
            'proposition_id' => $proposition->id,
            'parent_id'      => $demande->parent_id,
            'repetiteur_id'  => $proposition->repetiteur_id,
            'eleve_id'       => $demande->eleve_id,
            'matiere'        => implode(', ', $demande->matieres ?? []),
            'adresse'        => $demande->adresse_cours,
            'date_debut'     => $demande->date_debut_souhaitee ?? now(),
            'frequence'      => $demande->seances_semaine . ' séance(s) / semaine',
            'horaires'       => $demande->horaires_disponibles,
            'tarif_convenu'  => null,
            'statut'         => 'confirme',
            'commentaires'   => 'Placement créé automatiquement suite à l\'acceptation du parent.',
        ]);

        $demande->update(['statut' => 'placement_confirme']);

        // 🔔 NOTIF AUX ADMINS : proposition acceptée
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PropositionAccepteeNotification($proposition));
        }

        // 🔔 NOTIF AU PARENT : placement créé
        $demande->parent->notify(new PlacementCreeNotification($placement));

        // 🔔 NOTIF AU RÉPÉTITEUR : placement créé
        $proposition->repetiteur->user->notify(new PlacementCreeNotification($placement));

        return redirect()->route('parent.propositions.index')
            ->with('success', 'Proposition acceptée ! MON MAÎTRE va organiser le placement.');
    }

    public function refuser(Request $request, Proposition $proposition)
    {
        if ($proposition->demande->parent_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'raison_refus' => 'nullable|string|max:500',
        ]);

        $proposition->update([
            'statut'       => 'refusee',
            'date_reponse' => now(),
            'raison_refus' => $request->raison_refus,
        ]);

        $proposition->demande->update(['statut' => 'recherche_repetiteur']);

        // 🔔 NOTIF AUX ADMINS : proposition refusée
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PropositionRefuseeNotification($proposition));
        }

        return redirect()->route('parent.propositions.index')
            ->with('success', 'Vous avez demandé une autre proposition. MON MAÎTRE va en rechercher une nouvelle.');
    }
}