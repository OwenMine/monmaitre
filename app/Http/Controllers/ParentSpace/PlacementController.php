<?php

declare(strict_types=1);

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Placement;
use App\Support\AuthHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlacementController extends Controller
{
    public function index(): View
    {
        $placements = Placement::with(['repetiteur.user', 'eleve'])
            ->where('parent_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('parent.placements.index', compact('placements'));
    }

    public function show(Placement $placement): View
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($placement, champ: 'parent_id');

        $placement->load(['repetiteur.user', 'eleve', 'demande', 'evaluation', 'seances']);

        return view('parent.placements.show', compact('placement'));
    }

    /**
     * Enregistrer une évaluation du répétiteur.
     */
    public function storeEvaluation(Request $request, Placement $placement): RedirectResponse
    {
        // ⚡ D.1 : Vérification d'ownership via AuthHelper
        AuthHelper::verifierPropriete($placement, champ: 'parent_id');

        // ⚡ B.16 : Transaction + lock pour éviter la double évaluation
        $result = DB::transaction(function () use ($request, $placement) {
            // Recharger le placement avec lock
            $placement = Placement::lockForUpdate()->find($placement->id);

            if (! $placement) {
                return ['error' => 'Placement introuvable.'];
            }

            // Vérifier qu'il n'y a pas déjà une évaluation
            if (Evaluation::where('placement_id', $placement->id)->exists()) {
                return ['error' => 'Vous avez déjà évalué ce placement.'];
            }

            // Vérifier le statut du placement
            if (! in_array($placement->statut, ['termine', 'evaluation'])) {
                return ['error' => 'Le placement doit être terminé avant d\'être évalué.'];
            }

            $data = $request->validate([
                'note'        => 'required|integer|min:1|max:10',
                'commentaire' => 'nullable|string|max:1000',
            ]);

            $evaluation = Evaluation::create([
                'placement_id'  => $placement->id,
                'parent_id'     => auth()->id(),
                'repetiteur_id' => $placement->repetiteur_id,
                'note'          => $data['note'],
                'commentaire'   => $data['commentaire'] ?? null,
            ]);

            $placement->update(['statut' => 'termine']);

            return ['success' => true, 'evaluation' => $evaluation];
        });

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        return redirect()->route('parent.placements.show', $placement)
            ->with('success', 'Merci ! Votre évaluation a bien été enregistrée.');
    }
}