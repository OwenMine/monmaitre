<?php

namespace App\Http\Controllers\Repetiteur;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Seance;
use App\Notifications\NouvelleSeanceNotification;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    public function index(Placement $placement)
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur || $placement->repetiteur_id !== $repetiteur->id) {
            abort(403);
        }

        $seances = $placement->seances()
            ->orderByDesc('date')
            ->paginate(20);

        return view('repetiteur.seances.index', compact('placement', 'seances'));
    }

    public function create(Placement $placement)
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur || $placement->repetiteur_id !== $repetiteur->id) {
            abort(403);
        }

        return view('repetiteur.seances.create', compact('placement'));
    }

    public function store(Request $request, Placement $placement)
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur || $placement->repetiteur_id !== $repetiteur->id) {
            abort(403);
        }

        $data = $request->validate([
            'date'          => 'required|date|before_or_equal:today',
            'duree_minutes' => 'required|integer|min:15|max:300',
            'contenu'       => 'required|string|max:2000',
            'commentaire'   => 'nullable|string|max:1000',
            'statut'        => 'required|in:effectuee,absence_repetiteur,absence_eleve,annulee',
        ]);

        $data['placement_id']  = $placement->id;
        $data['repetiteur_id'] = $repetiteur->id;

        // ⚡ Si le statut est "effectuee", il passe en "en_attente" (validation par le parent)
        // Les autres statuts (absence, annulée) sont acceptés directement
        if ($data['statut'] === 'effectuee') {
            $data['statut'] = 'en_attente';
        }

        $seance = Seance::create($data);

        // 🔔 Notifier le parent (uniquement pour les séances en attente)
        if ($seance->statut === 'en_attente') {
            $placement->parent->notify(new NouvelleSeanceNotification($seance));
        }

        return redirect()->route('repetiteur.seances.index', $placement)
            ->with('success', 'Séance enregistrée. Elle sera validée par le parent.');
    }

    public function destroy(Seance $seance)
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur || $seance->repetiteur_id !== $repetiteur->id) {
            abort(403);
        }

        // Sécurité : on ne supprime que les séances en attente
        if (! $seance->estEnAttente()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez supprimer qu\'une séance en attente de validation.');
        }

        $placement = $seance->placement;
        $seance->delete();

        return redirect()->route('repetiteur.seances.index', $placement)
            ->with('success', 'Séance supprimée.');
    }
}