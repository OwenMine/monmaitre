<?php

namespace App\Http\Controllers\Repetiteur;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use Illuminate\Support\Facades\Gate;

class PlacementController extends Controller
{
    public function index()
    {
        $repetiteur = auth()->user()->repetiteur;

        if (!$repetiteur) {
            return redirect()->route('repetiteur.dashboard')
                ->with('error', 'Vous devez compléter votre profil pour voir vos missions.');
        }

        $placements = Placement::with(['parent', 'eleve'])
            ->where('repetiteur_id', $repetiteur->id)
            ->latest()
            ->paginate(10);

        return view('repetiteur.placements.index', compact('placements'));
    }

    public function show(Placement $placement)
    {
        $repetiteur = auth()->user()->repetiteur;

        // Sécurité : un répétiteur ne voit que ses propres placements
        $this->authorize('view', $placement);

        $placement->load(['parent', 'eleve', 'demande']);

        return view('repetiteur.placements.show', compact('placement'));
    }
}