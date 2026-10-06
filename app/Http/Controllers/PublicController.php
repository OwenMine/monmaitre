<?php

namespace App\Http\Controllers;

use App\Models\Repetiteur;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    /**
     * Page d'accueil publique.
     */
    public function accueil()
    {
        // Récupérer les 4 derniers répétiteurs validés AVEC photo
        $repetiteurs = Repetiteur::with('user')
            ->where('statut', 'profil_valide')
            ->whereNotNull('photo')
            ->latest()
            ->limit(4)
            ->get();

        // Calculer la note moyenne et le nombre d'avis pour chaque répétiteur
        foreach ($repetiteurs as $rep) {
            $rep->note_moyenne = round($rep->evaluations()->avg('note') ?? 0, 1);
            $rep->nb_avis = $rep->evaluations()->count();
        }

        return view('public.accueil', compact('repetiteurs'));
    }

    /**
     * Sert la photo de profil d'un répétiteur.
     * Contourne les problèmes de symlink storage sur Windows/XAMPP.
     */
    public function photoRepetiteur(Repetiteur $repetiteur)
{
    if (! $repetiteur->photo || ! Storage::disk('public')->exists($repetiteur->photo)) {
        abort(404);
    }

    $path = Storage::disk('public')->path($repetiteur->photo);
    $mime = Storage::disk('public')->mimeType($repetiteur->photo) ?? 'image/jpeg';

    // Récupérer la date de dernière modification pour créer une version unique
    $lastModified = filemtime($path);
    $etag = md5_file($path);

    return response()->file($path, [
        'Content-Type'   => $mime,
        'Cache-Control'  => 'no-cache, no-store, must-revalidate, max-age=0',
        'Pragma'         => 'no-cache',
        'Expires'        => '0',
        'ETag'           => '"' . $etag . '"',
        'Last-Modified'  => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
    ]);
}
}