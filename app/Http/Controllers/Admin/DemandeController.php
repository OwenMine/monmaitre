<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Notifications\DemandeStatutChangeNotification;
use App\Services\AuditLogger;
use Illuminate\Http\Request;


class DemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Demande::with(['parent', 'eleve']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereHas('parent', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('eleve', function ($sub) use ($search) {
                    $sub->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                })
                ->orWhere('matieres', 'like', "%{$search}%");
            });
        }

        $demandes = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'toutes'    => Demande::count(),
            'nouvelles' => Demande::where('statut', 'demande_envoyee')->count(),
            'en_cours'  => Demande::whereIn('statut', ['en_analyse', 'recherche_repetiteur', 'repetiteur_identifie', 'repetiteur_contacte', 'attente_confirmation_parent'])->count(),
            'placees'   => Demande::whereIn('statut', ['placement_confirme', 'en_cours'])->count(),
            'terminees' => Demande::where('statut', 'termine')->count(),
        ];

        return view('admin.demandes.index', compact('demandes', 'counts'));
    }

    public function show(Demande $demande)
    {
        $demande->load(['parent', 'eleve']);

        return view('admin.demandes.show', compact('demande'));
    }

    public function changerStatut(Request $request, Demande $demande)
    {
        $request->validate([
            'statut' => 'required|in:dossier_incomplet,dossier_complet,demande_envoyee,en_analyse,recherche_repetiteur,repetiteur_identifie,repetiteur_contacte,attente_confirmation_parent,placement_confirme,en_cours,termine,annule,remplacement_demande',
        ]);

        $ancienStatut = $demande->statut;
        $demande->update(['statut' => $request->statut]);

        if ($ancienStatut !== $request->statut) {
            $demande->parent->notify(new DemandeStatutChangeNotification($demande));
        }

        AuditLogger::admin('DEMANDE_STATUT_CHANGE',
            "Changement de statut demande #MM-" . str_pad($demande->id, 4, '0', STR_PAD_LEFT) . " : {$ancienStatut} → {$request->statut}",
            [
                'demande_id' => $demande->id,
                'parent_id' => $demande->parent_id,
                'ancien_statut' => $ancienStatut,
                'nouveau_statut' => $request->statut,
            ]
        );

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }
}