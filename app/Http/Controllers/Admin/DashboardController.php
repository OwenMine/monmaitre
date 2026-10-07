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
        // ============================================
        // STATS GLOBALES (optimisées avec cache 60s)
        // ============================================
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 60, function () {
            return [
                'total_parents'       => User::where('role', 'parent')->count(),
                'total_repetiteurs'   => User::where('role', 'repetiteur')->count(),
                'repetiteurs_valides' => Repetiteur::where('statut', 'profil_valide')->count(),
                'candidatures_attente' => Repetiteur::whereIn('statut', [
                    'candidature_envoyee',
                    'a_examiner',
                    'documents_a_verifier',
                ])->count(),
                'total_demandes'      => Demande::count(),
                'demandes_en_cours'   => Demande::whereNotIn('statut', ['termine', 'annule'])->count(),
                'demandes_nouvelles'  => Demande::where('statut', 'demande_envoyee')->count(),
                'placements_actifs'   => Demande::where('statut', 'en_cours')->count(),
            ];
        });

        // ============================================
        // DERNIÈRES DEMANDES — Colonnes sélectionnées
        // ============================================
        $dernieresDemandes = Demande::query()
            ->select(['id', 'parent_id', 'eleve_id', 'statut', 'created_at'])
            ->with([
                'parent:id,name,phone',
                'eleve:id,nom,prenom,classe',
            ])
            ->latest()
            ->limit(5)
            ->get();

        // ============================================
        // DERNIERS RÉPÉTITEURS — Colonnes sélectionnées
        // ============================================
        $derniersRepetiteurs = Repetiteur::query()
            ->select(['id', 'user_id', 'matieres', 'statut', 'created_at'])
            ->with([
                'user:id,name,email,phone,commune',
            ])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'dernieresDemandes', 'derniersRepetiteurs'));
    }
}