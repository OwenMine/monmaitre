<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PlacementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Placement::with(['parent', 'repetiteur.user', 'eleve']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('parent', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('eleve', function ($sub) use ($search) {
                      $sub->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        $placements = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'tous'      => Placement::count(),
            'confirmes' => Placement::where('statut', 'confirme')->count(),
            'en_cours'  => Placement::where('statut', 'en_cours')->count(),
            'termines'  => Placement::where('statut', 'termine')->count(),
        ];

        return view('admin.placements.index', compact('placements', 'counts'));
    }

    public function show(Placement $placement): View
    {
        $this->authorize('view', $placement);

        $placement->load(['parent', 'eleve', 'repetiteur.user', 'demande', 'evaluation', 'seances.valideePar']);

        return view('admin.placements.show', compact('placement'));
    }

    public function update(Request $request, Placement $placement): RedirectResponse
    {
        $data = $request->validate([
            'statut'        => 'required|in:en_attente,confirme,en_cours,evaluation,termine,remplacement,annule',
            'tarif_convenu' => 'nullable|numeric|min:0',
            'commentaires'  => 'nullable|string|max:2000',
        ]);

        $ancienStatut = $placement->statut;
        $placement->update($data);

        AuditLogger::admin(
            'PLACEMENT_UPDATE',
            "Mise à jour du placement {$placement->reference}",
            [
                'placement_id'   => $placement->id,
                'ancien_statut'  => $ancienStatut,
                'nouveau_statut' => $data['statut'],
            ]
        );

        return redirect()->back()->with('success', 'Placement mis à jour.');
    }

    /**
     * Export CSV des placements (respecte les filtres actifs).
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Placement::with(['parent', 'repetiteur.user', 'eleve', 'evaluation'])
            ->withCount([
                'seances as seances_effectuees_count' => fn ($q) => $q->where('statut', 'effectuee'),
                'seances as seances_total_count',
            ]);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('parent', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('eleve', function ($sub) use ($search) {
                      $sub->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        $placements = $query->latest()->get();

        $filename = 'placements_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        AuditLogger::admin(
            'EXPORT_CSV',
            "Export CSV des placements ({$placements->count()} lignes)",
            [
                'nombre_lignes' => $placements->count(),
                'filtres'       => $request->only(['statut', 'q']),
            ]
        );

        $callback = function () use ($placements) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel (accents corrects)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Référence', 'Date création', 'Statut', 'Parent', 'Téléphone parent',
                'Élève', 'Classe', 'Répétiteur', 'Téléphone répétiteur', 'Matière',
                'Adresse', 'Date début', 'Fréquence', 'Horaires', 'Tarif convenu (FCFA)',
                'Séances effectuées', 'Séances totales', 'Note évaluation', 'Commentaires',
            ], ';');

            foreach ($placements as $p) {
                fputcsv($file, [
                    $p->reference,
                    $p->created_at->format('d/m/Y H:i'),
                    $p->statutLabel(),
                    $p->parent->name,
                    $p->parent->phone,
                    $p->eleve->prenom . ' ' . $p->eleve->nom,
                    $p->eleve->classe,
                    $p->repetiteur->user->name,
                    $p->repetiteur->user->phone,
                    $p->matiere,
                    $p->adresse,
                    $p->date_debut?->format('d/m/Y') ?? '',
                    $p->frequence ?? '',
                    $p->horaires ?? '',
                    $p->tarif_convenu ? number_format((float) $p->tarif_convenu, 0, ',', '') : '',
                    $p->seances_effectuees_count,
                    $p->seances_total_count,
                    $p->evaluation?->note ?? '',
                    $p->commentaires ?? '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}