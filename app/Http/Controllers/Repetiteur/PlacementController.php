<?php

declare(strict_types=1);

namespace App\Http\Controllers\Repetiteur;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlacementController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur) {
            return redirect()->route('repetiteur.dashboard')
                ->with('error', 'Vous devez compléter votre profil pour voir vos missions.');
        }

        $placements = Placement::with(['parent', 'eleve'])
            ->where('repetiteur_id', $repetiteur->id)
            ->latest()
            ->paginate(10);

        return view('repetiteur.placements.index', compact('placements'));
    }

    public function show(Placement $placement): View
    {
        $this->authorize('view', $placement);

        $placement->load(['parent', 'eleve', 'demande', 'evaluation', 'seances']);

        return view('repetiteur.placements.show', compact('placement'));
    }
}