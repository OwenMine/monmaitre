<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Placement;
use App\Models\Seance;
use Illuminate\Support\Facades\DB;

class SuiviController extends Controller
{
    /**
     * Dashboard de suivi global.
     */
    public function index()
    {
        // ============================================
        // KPI GLOBAUX
        // ============================================
        $stats = [
            'placements_actifs'    => Placement::whereIn('statut', ['confirme', 'en_cours'])->count(),
            'placements_termines'  => Placement::where('statut', 'termine')->count(),
            'seances_ce_mois'      => Seance::where('statut', 'effectuee')
                                        ->whereMonth('date', now()->month)
                                        ->whereYear('date', now()->year)
                                        ->count(),
            'note_moyenne_globale' => round(Evaluation::avg('note') ?? 0, 1),
            'taux_presence_global' => $this->calculerTauxPresenceGlobal(),
            'absences_ce_mois'     => Seance::where('statut', 'absence_repetiteur')
                                        ->whereMonth('date', now()->month)
                                        ->whereYear('date', now()->year)
                                        ->count(),
        ];

        // ============================================
        // GRAPHIQUE 1 : Évolution des séances (12 dernières semaines) OPTIMISATION : 1 requête au lieu de 12
        // ============================================
       
        $debutGlobal = now()->subWeeks(11)->startOfWeek();
        $finGlobal   = now()->endOfWeek();

        // Compter les séances par semaine en 1 seule requête
        $seancesParSemaineDB = Seance::where('statut', 'effectuee')
            ->whereBetween('date', [$debutGlobal, $finGlobal])
            ->selectRaw('YEARWEEK(date, 1) as semaine, COUNT(*) as total')
            ->groupBy('semaine')
            ->pluck('total', 'semaine')
            ->toArray();

        // Construire le tableau final (12 semaines)
        $seancesParSemaine = [];
        for ($i = 11; $i >= 0; $i--) {
            $debut = now()->subWeeks($i)->startOfWeek();
            $semaine = (int) $debut->format('oW'); // Format ISO : année + semaine

            $seancesParSemaine[] = [
                'label' => $debut->format('d/m'),
                'count' => $seancesParSemaineDB[$semaine] ?? 0,
            ];
        }

        // ============================================
        // GRAPHIQUE 2 : Répartition des statuts de placement
        // ============================================
        $repartitionStatuts = Placement::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        // ============================================
        // TABLEAU : Placements actifs avec leurs indicateurs
        // ============================================
        $placementsActifs = Placement::with(['parent', 'eleve', 'repetiteur.user', 'evaluation'])
            ->whereIn('statut', ['confirme', 'en_cours'])
            ->withCount([
                'seances as seances_effectuees_count' => fn ($q) => $q->where('statut', 'effectuee'),
                'seances as seances_absence_count'    => fn ($q) => $q->where('statut', 'absence_repetiteur'),
                'seances as seances_total_count',
            ])
            ->latest()
            ->paginate(15);

        // ============================================
        // ALERTES
        // ============================================
        $alertes = $this->genererAlertes();

        return view('admin.suivi.index', compact(
            'stats',
            'seancesParSemaine',
            'repartitionStatuts',
            'placementsActifs',
            'alertes'
        ));
    }

    /**
     * Calcule le taux de présence global (%).
     */
    protected function calculerTauxPresenceGlobal(): float
    {
        $total = Seance::count();
        if ($total === 0) return 0;

        $effectuees = Seance::where('statut', 'effectuee')->count();

        return round(($effectuees / $total) * 100, 1);
    }

    /**
     * Génère la liste des alertes.
     */
    protected function genererAlertes(): array
    {
        $alertes = [];

        // 1. Placements actifs sans séance depuis 14 jours
        $placementsInactifs = Placement::with(['eleve', 'repetiteur.user'])
            ->whereIn('statut', ['confirme', 'en_cours'])
            ->whereDoesntHave('seances', function ($q) {
                $q->where('date', '>=', now()->subDays(14));
            })
            ->get();

        foreach ($placementsInactifs as $p) {
            $alertes[] = [
                'type'    => 'inactivite',
                'couleur' => 'orange',
                'icone'   => 'clock',
                'titre'   => 'Aucune séance depuis 14 jours',
                'message' => 'Le placement ' . $p->reference . ' (' . $p->eleve->prenom . ') n\'a eu aucune séance récente.',
                'url'     => route('admin.placements.show', $p),
            ];
        }

        // 2. Répétiteurs avec 2+ absences ce mois
        $repetiteursAbsents = Seance::select('repetiteur_id', DB::raw('count(*) as absences'))
            ->where('statut', 'absence_repetiteur')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('repetiteur_id')
            ->having('absences', '>=', 2)
            ->with('repetiteur.user')
            ->get();

        foreach ($repetiteursAbsents as $s) {
            if ($s->repetiteur) {
                $alertes[] = [
                    'type'    => 'absences',
                    'couleur' => 'red',
                    'icone'   => 'bell',
                    'titre'   => 'Absences répétées',
                    'message' => $s->repetiteur->user->name . ' a ' . $s->absences . ' absences ce mois-ci.',
                    'url'     => route('admin.repetiteurs.show', $s->repetiteur),
                ];
            }
        }

        // 3. Évaluations avec note basse (≤ 4)
        $evaluationsBasses = Evaluation::with(['repetiteur.user', 'placement'])
            ->where('note', '<=', 4)
            ->latest()
            ->limit(5)
            ->get();

        foreach ($evaluationsBasses as $e) {
            $alertes[] = [
                'type'    => 'note_basse',
                'couleur' => 'red',
                'icone'   => 'target',
                'titre'   => 'Note basse reçue',
                'message' => $e->repetiteur->user->name . ' a reçu une note de ' . $e->note . '/10.',
                'url'     => route('admin.placements.show', $e->placement),
            ];
        }

        return $alertes;
    }
}