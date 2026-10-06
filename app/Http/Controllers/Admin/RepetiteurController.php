<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Repetiteur;
use App\Notifications\ProfilValideNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\AuditLogger;

class RepetiteurController extends Controller
{
    public function index(Request $request)
    {
        $query = Repetiteur::with('user');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('matiere')) {
            $query->where('matieres', 'like', '%' . $request->matiere . '%');
        }

        if ($request->filled('commune')) {
            $query->where('communes', 'like', '%' . $request->commune . '%');
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $repetiteurs = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'tous'    => Repetiteur::count(),
            'attente' => Repetiteur::whereIn('statut', ['candidature_envoyee', 'a_examiner', 'documents_a_verifier', 'profil_en_attente'])->count(),
            'valides' => Repetiteur::where('statut', 'profil_valide')->count(),
            'refuses' => Repetiteur::whereIn('statut', ['profil_refuse', 'profil_suspendu'])->count(),
        ];

        return view('admin.repetiteurs.index', compact('repetiteurs', 'counts'));
    }

    public function show(Repetiteur $repetiteur)
    {
        $repetiteur->load(['user', 'documents']);

        return view('admin.repetiteurs.show', compact('repetiteur'));
    }

    public function valider(Repetiteur $repetiteur)
{
    $repetiteur->update(['statut' => 'profil_valide']);

    // ⚡ Marquer l'email comme vérifié (validation admin)
    if (! $repetiteur->user->email_verified_at) {
        $repetiteur->user->update(['email_verified_at' => now()]);
    }

    // 🔔 NOTIF AU RÉPÉTITEUR
    $repetiteur->user->notify(new ProfilValideNotification($repetiteur));

    AuditLogger::admin('REPETITEUR_VALIDE',
        "Validation du profil de {$repetiteur->user->name}",
        ['repetiteur_id' => $repetiteur->id, 'user_id' => $repetiteur->user_id]
    );

    // 📝 Log d'audit
    AuditLogger::admin('REPETITEUR_VALIDE', 
        "Validation du profil de {$repetiteur->user->name}", 
        [
            'repetiteur_id' => $repetiteur->id,
            'user_id' => $repetiteur->user_id,
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => 'profil_valide',
        ]
    );

    return redirect()->back()->with('success', 'Profil validé avec succès.');
}

public function refuser(Repetiteur $repetiteur)
{
    $ancienStatut = $repetiteur->statut;
    $repetiteur->update(['statut' => 'profil_refuse']);

    AuditLogger::admin('REPETITEUR_REFUSE',
        "Refus du profil de {$repetiteur->user->name}",
        [
            'repetiteur_id' => $repetiteur->id,
            'user_id' => $repetiteur->user_id,
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => 'profil_refuse',
        ]
    );

    return redirect()->back()->with('success', 'Profil refusé.');
}

public function suspendre(Repetiteur $repetiteur)
{
    $repetiteur->update(['statut' => 'profil_suspendu']);

    // ⚡ Déconnecter toutes les sessions du répétiteur
    $repetiteur->user->update(['status' => 'suspendu']);

    // Supprimer ses sessions actives
    \DB::table('sessions')
        ->where('user_id', $repetiteur->user->id)
        ->delete();

    AuditLogger::admin('REPETITEUR_SUSPENDU',
        "Suspension du profil de {$repetiteur->user->name}",
        ['repetiteur_id' => $repetiteur->id, 'user_id' => $repetiteur->user_id]
    );

    return redirect()->back()->with('success', 'Profil suspendu. Le répétiteur a été déconnecté.');
}

public function changerStatut(Request $request, Repetiteur $repetiteur)
{
    $request->validate([
        'statut' => 'required|in:pre_inscrit,dossier_incomplet,candidature_envoyee,a_examiner,entretien_a_effectuer,documents_a_verifier,profil_valide,profil_en_attente,profil_refuse,profil_suspendu',
    ]);

    $ancienStatut = $repetiteur->statut;
    $repetiteur->update(['statut' => $request->statut]);

    if ($request->statut === 'profil_valide' && ! $repetiteur->user->email_verified_at) {
    $repetiteur->user->update(['email_verified_at' => now()]);
    }

    AuditLogger::admin('REPETITEUR_STATUT_CHANGE',
        "Changement de statut de {$repetiteur->user->name} : {$ancienStatut} → {$request->statut}",
        [
            'repetiteur_id' => $repetiteur->id,
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => $request->statut,
        ]
    );

    return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
}

    // ============================================================
    // DOCUMENTS
    // ============================================================

    public function voirDocument(Document $document)
    {
        if (! Storage::disk('local')->exists($document->chemin)) {
            abort(404, 'Fichier introuvable.');
        }

        $path = Storage::disk('local')->path($document->chemin);
        $mimeType = Storage::disk('local')->mimeType($document->chemin) ?? 'application/octet-stream';

        return response()->file($path, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->nom_fichier . '"',
        ]);
    }

    public function telechargerDocument(Document $document): StreamedResponse
    {
        if (! Storage::disk('local')->exists($document->chemin)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('local')->download($document->chemin, $document->nom_fichier);
    }
}