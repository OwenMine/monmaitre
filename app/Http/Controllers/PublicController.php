<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Repetiteur;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicController extends Controller
{
    public function accueil(): View
    {
        $repetiteurs = Repetiteur::with('user')
            ->where('statut', 'profil_valide')
            ->whereNotNull('photo')
            ->latest()
            ->limit(4)
            ->get();

        foreach ($repetiteurs as $rep) {
            $rep->note_moyenne = round((float) ($rep->evaluations()->avg('note') ?? 0), 1);
            $rep->nb_avis = $rep->evaluations()->count();
        }

        return view('public.accueil', compact('repetiteurs'));
    }

    /**
     * Sert la photo de profil d'un répétiteur.
     * Contourne les problèmes de symlink storage sur Windows/XAMPP.
     */
    public function photoRepetiteur(Repetiteur $repetiteur): BinaryFileResponse
    {
        if (! $repetiteur->photo || ! Storage::disk('public')->exists($repetiteur->photo)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($repetiteur->photo);
        $mime = Storage::disk('public')->mimeType($repetiteur->photo) ?? 'image/jpeg';

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