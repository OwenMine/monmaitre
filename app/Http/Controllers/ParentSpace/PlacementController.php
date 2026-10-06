<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Placement;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function index()
    {
        $placements = Placement::with(['repetiteur.user', 'eleve'])
            ->where('parent_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('parent.placements.index', compact('placements'));
    }

    public function show(Placement $placement)
    {
        $this->authorize('view', $placement);

        $placement->load(['repetiteur.user', 'eleve', 'demande', 'evaluation', 'seances']);

        return view('parent.placements.show', compact('placement'));
    }

    /**
     * Enregistrer une évaluation du répétiteur.
     */
    public function storeEvaluation(Request $request, Placement $placement)
    {
        if ($placement->parent_id !== auth()->id()) {
            abort(403);
        }

        // Vérifier que l'évaluation n'existe pas déjà
        if ($placement->evaluation) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà évalué ce placement.');
        }

        // Vérifier que le placement est éligible
        if (! in_array($placement->statut, ['termine', 'evaluation'])) {
            return redirect()->back()
                ->with('error', 'Le placement doit être terminé avant d\'être évalué.');
        }

        $data = $request->validate([
            'note'        => 'required|integer|min:1|max:10',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        Evaluation::create([
            'placement_id'  => $placement->id,
            'parent_id'     => auth()->id(),
            'repetiteur_id' => $placement->repetiteur_id,
            'note'          => $data['note'],
            'commentaire'   => $data['commentaire'] ?? null,
        ]);

        // Optionnel : passer le placement en "évaluation terminée"
        $placement->update(['statut' => 'termine']);

        return redirect()->route('parent.placements.show', $placement)
            ->with('success', 'Merci ! Votre évaluation a bien été enregistrée.');
    }
}