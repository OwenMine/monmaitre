<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Repetiteur;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_parents' => User::where('role', 'parent')->count(),
            'total_repetiteurs' => User::where('role', 'repetiteur')->count(),
            'repetiteurs_valides' => Repetiteur::where('statut', 'profil_valide')->count(),
            'candidatures_attente' => Repetiteur::whereIn('statut', [
                'candidature_envoyee',
                'a_examiner',
                'documents_a_verifier',
            ])->count(),
            'total_demandes' => Demande::count(),
            'demandes_en_cours' => Demande::whereNotIn('statut', ['termine', 'annule'])->count(),
            'demandes_nouvelles' => Demande::where('statut', 'demande_envoyee')->count(),
            'placements_actifs' => Demande::where('statut', 'en_cours')->count(),
        ];

        $dernieresDemandes = Demande::with(['parent', 'eleve'])
            ->latest()
            ->limit(5)
            ->get();

        $derniersRepetiteurs = Repetiteur::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'dernieresDemandes', 'derniersRepetiteurs'));
    }
}