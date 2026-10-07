<?php

namespace App\Http\Controllers\Repetiteur;

use App\Http\Controllers\Controller;
use App\Models\Seance;

class DashboardController extends Controller
{
    public function index()
    {
        $repetiteur = auth()->user()->repetiteur;

        // Si l'utilisateur n'a pas de profil répétiteur
        if (! $repetiteur) {
            return view('repetiteur.dashboard', [
                'repetiteur' => null,
                'stats'      => null,
                'seancesParSemaine' => [],
                'badges'            => [],
                'dernieresMissions' => collect(),
            ]);
        }

        // ============================================
        // STATS GLOBALES
        // ============================================
        $missionsActives = $repetiteur->placements()
            ->whereIn('statut', ['confirme', 'en_cours'])
            ->count();

        $missionsTotal = $repetiteur->placements()->count();

        // ⚡ D.2 : Stats agrégées en 1 requête
        $statsGlobales = \App\Models\Seance::where('repetiteur_id', $repetiteur->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'effectuee' THEN 1 ELSE 0 END) as effectuees,
                SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as en_attente,
                SUM(CASE WHEN statut = 'absence_repetiteur' THEN 1 ELSE 0 END) as absences,
                SUM(CASE WHEN statut = 'effectuee' THEN duree_minutes ELSE 0 END) as minutes
            ")
            ->first();

        $seancesEffectuees = (int) $statsGlobales->effectuees;
        $seancesEnAttente  = (int) $statsGlobales->en_attente;
        $absences          = (int) $statsGlobales->absences;
        $heuresTotales     = round($statsGlobales->minutes / 60, 1);
        $totalSeances      = (int) $statsGlobales->total;
        $tauxPresence      = $totalSeances > 0
            ? round(($seancesEffectuees / $totalSeances) * 100, 1)
            : 0.0;

        $noteMoyenne = $repetiteur->evaluations()->avg('note') ?? 0;
        $noteMoyenne = round($noteMoyenne, 1);

        $nbEvaluations = $repetiteur->evaluations()->count();

        $revenusEstimes = $seancesEffectuees * 5000;

        // ============================================
        // GRAPHIQUE
        // ============================================
        $seancesParSemaine = [];
        for ($i = 3; $i >= 0; $i--) {
            $debut = now()->subWeeks($i)->startOfWeek();
            $fin   = now()->subWeeks($i)->endOfWeek();

            $seancesParSemaine[] = [
                'label' => 'Sem. ' . $debut->format('d/m'),
                'count' => Seance::where('repetiteur_id', $repetiteur->id)
                    ->where('statut', 'effectuee')
                    ->whereBetween('date', [$debut, $fin])
                    ->count(),
            ];
        }

        // ============================================
        // BADGES
        // ============================================
        $badges = [];

        if ($seancesEffectuees >= 10) {
            $badges[] = ['icon' => 'check-badge', 'label' => 'Répétiteur actif', 'color' => 'green'];
        }

        if ($noteMoyenne >= 8 && $nbEvaluations > 0) {
            $badges[] = ['icon' => 'academic', 'label' => 'Très bien noté', 'color' => 'mm-accent'];
        }

        if ($tauxPresence >= 90 && $totalSeances > 0) {
            $badges[] = ['icon' => 'target', 'label' => 'Toujours ponctuel', 'color' => 'mm-secondary'];
        }

        if ($repetiteur->experience_annees >= 3) {
            $badges[] = ['icon' => 'user-circle', 'label' => 'Expérimenté', 'color' => 'mm-navy'];
        }

        // ============================================
        // DERNIÈRES MISSIONS
        // ============================================
        $dernieresMissions = $repetiteur->placements()
            ->with(['parent', 'eleve'])
            ->latest()
            ->limit(3)
            ->get();

        // ============================================
        // STATS FINALES
        // ============================================
        $stats = [
            'missions_actives'    => $missionsActives,
            'missions_total'      => $missionsTotal,
            'seances_effectuees'  => $seancesEffectuees,
            'seances_en_attente'  => $seancesEnAttente,
            'heures_totales'      => $heuresTotales,
            'absences'            => $absences,
            'taux_presence'       => $tauxPresence,
            'note_moyenne'        => $noteMoyenne,
            'nb_evaluations'      => $nbEvaluations,
            'revenus_estimes'     => $revenusEstimes,
        ];

        return view('repetiteur.dashboard', compact(
            'repetiteur',
            'stats',
            'seancesParSemaine',
            'badges',
            'dernieresMissions'
        ));
    }
}