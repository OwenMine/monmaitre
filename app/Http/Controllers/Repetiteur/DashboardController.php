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

        $seancesEffectuees = Seance::where('repetiteur_id', $repetiteur->id)
            ->where('statut', 'effectuee')
            ->count();

        $seancesEnAttente = Seance::where('repetiteur_id', $repetiteur->id)
            ->where('statut', 'en_attente')
            ->count();

        $totalMinutes = (int) Seance::where('repetiteur_id', $repetiteur->id)
            ->where('statut', 'effectuee')
            ->sum('duree_minutes');

        $heuresTotales = round($totalMinutes / 60, 1);

        $absences = Seance::where('repetiteur_id', $repetiteur->id)
            ->where('statut', 'absence_repetiteur')
            ->count();

        $totalSeances = Seance::where('repetiteur_id', $repetiteur->id)->count();
        $tauxPresence = $totalSeances > 0
            ? round(($seancesEffectuees / $totalSeances) * 100, 1)
            : 0;

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