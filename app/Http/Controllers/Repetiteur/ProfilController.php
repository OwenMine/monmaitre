<?php

declare(strict_types=1);

namespace App\Http\Controllers\Repetiteur;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Repetiteur;
use App\Rules\ValidFileType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur) {
            $repetiteur = Repetiteur::create([
                'user_id' => auth()->id(),
                'statut'  => 'dossier_incomplet',
            ]);
        }

        return view('repetiteur.profil.edit', compact('repetiteur'));
    }

    public function update(Request $request): RedirectResponse
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur) {
            return redirect()->back()
                ->with('error', 'Profil introuvable. Complétez-le d\'abord.');
        }

        $data = $request->validate([
            'niveau_etudes'   => 'required|string|max:100',
            'diplome'         => 'required|string|max:150',
            'etablissement'   => 'nullable|string|max:150',
            'annee_obtention' => 'nullable|integer|min:1950|max:' . date('Y'),

            'matieres_text' => 'required|string',
            'classes_text'  => 'required|string',
            'niveau_min'    => 'required|string|max:50',
            'niveau_max'    => 'required|string|max:50',

            'experience_annees'         => 'required|integer|min:0|max:50',
            'experience_repetition'     => 'nullable|string|max:2000',
            'experiences_particulieres' => 'nullable|string|max:2000',
            'references'                => 'nullable|string|max:2000',

            'jours_disponibles'    => 'required|array|min:1',
            'horaires_disponibles' => 'required|string|max:255',
            'max_eleves'           => 'required|integer|min:1|max:20',

            'communes_text'     => 'required|string',
            'quartiers_text'    => 'nullable|string',
            'rayon_deplacement' => 'required|integer|min:1|max:100',
        ]);

        try {
            DB::transaction(function () use ($data, $repetiteur) {
                $data['matieres']  = array_values(array_filter(array_map('trim', explode("\n", $data['matieres_text']))));
                $data['classes']   = array_values(array_filter(array_map('trim', explode("\n", $data['classes_text']))));
                $data['communes']  = array_values(array_filter(array_map('trim', explode("\n", $data['communes_text']))));
                $data['quartiers'] = ! empty($data['quartiers_text'])
                    ? array_values(array_filter(array_map('trim', explode("\n", $data['quartiers_text']))))
                    : [];

                unset(
                    $data['matieres_text'],
                    $data['classes_text'],
                    $data['communes_text'],
                    $data['quartiers_text']
                );

                $repetiteur->update($data);
            });

            return redirect()->route('repetiteur.profil.edit')
                ->with('success', 'Votre profil a été enregistré.');

        } catch (\Throwable $e) {
            Log::error('Erreur sauvegarde profil répétiteur', [
                'repetiteur_id' => $repetiteur->id,
                'user_id'       => auth()->id(),
                'error'         => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement. Réessayez.');
        }
    }

    public function uploadPhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => [
                'required',
                'file',
                'max:2048',
                new ValidFileType(['image/jpeg', 'image/jpg', 'image/png']),
            ],
        ]);

        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur) {
            return redirect()->back()->with('error', 'Profil introuvable.');
        }

        if ($repetiteur->photo && Storage::disk('public')->exists($repetiteur->photo)) {
            Storage::disk('public')->delete($repetiteur->photo);
        }

        $path = $request->file('photo')->store('repetiteurs/photos', 'public');

        $repetiteur->update(['photo' => $path]);

        return redirect()->route('repetiteur.profil.edit')
            ->with('success', 'Photo de profil mise à jour.');
    }

    public function supprimerPhoto(): RedirectResponse
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur || ! $repetiteur->photo) {
            return redirect()->back()->with('error', 'Aucune photo à supprimer.');
        }

        if (Storage::disk('public')->exists($repetiteur->photo)) {
            Storage::disk('public')->delete($repetiteur->photo);
        }

        $repetiteur->update(['photo' => null]);

        return redirect()->route('repetiteur.profil.edit')
            ->with('success', 'Photo de profil supprimée.');
    }

    public function envoyerCandidature(): RedirectResponse
    {
        $repetiteur = auth()->user()->repetiteur;

        if (! $repetiteur || ! $repetiteur->diplome || ! $repetiteur->matieres) {
            return redirect()->route('repetiteur.profil.edit')
                ->with('error', 'Veuillez compléter votre profil avant d\'envoyer votre candidature.');
        }

        if ($repetiteur->documents()->count() === 0) {
            return redirect()->route('repetiteur.profil.edit')
                ->with('error', 'Veuillez ajouter au moins un document (CV) avant d\'envoyer votre candidature.');
        }

        $repetiteur->update(['statut' => 'candidature_envoyee']);

        return redirect()->route('repetiteur.dashboard')
            ->with('success', 'Votre candidature a été envoyée à MON MAÎTRE.');
    }

    public function uploadDocument(Request $request): RedirectResponse
    {
        $request->validate([
            'type'    => 'required|in:cv,diplome,certificat,autre',
            'fichier' => [
                'required',
                'file',
                'max:5120',
                new ValidFileType(['application/pdf', 'image/jpeg', 'image/jpg', 'image/png']),
            ],
        ]);

        $repetiteur = auth()->user()->repetiteur;

        $fichier = $request->file('fichier');

        $nomOriginal = str_replace(
            ['/', '\\', '..', "\0"],
            '_',
            $fichier->getClientOriginalName()
        );
        $nomOriginal = preg_replace('/[\x00-\x1F\x7F]/u', '', $nomOriginal);
        $nomOriginal = mb_substr($nomOriginal, 0, 200);

        $chemin = $fichier->store('documents/' . $repetiteur->id, 'local');

        Document::create([
            'repetiteur_id' => $repetiteur->id,
            'type'          => $request->type,
            'nom_fichier'   => $nomOriginal,
            'chemin'        => $chemin,
        ]);

        return redirect()->route('repetiteur.profil.edit')
            ->with('success', 'Document ajouté avec succès.');
    }

    public function supprimerDocument(Document $document): RedirectResponse
    {
        if ($document->repetiteur->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('local')->delete($document->chemin);
        $document->delete();

        return redirect()->route('repetiteur.profil.edit')
            ->with('success', 'Document supprimé.');
    }
}