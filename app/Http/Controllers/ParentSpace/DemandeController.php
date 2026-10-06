<?php

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Eleve;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = Demande::where('parent_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('parent.demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('parent.demandes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Élève
            'eleve_nom'            => 'required|string|max:100',
            'eleve_prenom'         => 'required|string|max:100',
            'eleve_age'            => 'nullable|integer|min:3|max:30',
            'eleve_classe'         => 'required|string|max:50',
            'eleve_etablissement'  => 'nullable|string|max:150',

            // Besoin pédagogique
            'matieres_text'        => 'required|string',
            'objectif'             => 'required|string|max:1000',
            'difficultes'          => 'nullable|string|max:1000',
            'preparation_examen'   => 'nullable|string|max:100',

            // Organisation
            'seances_semaine'      => 'required|integer|min:1|max:14',
            'duree_seance'         => 'required|integer|min:30|max:240',
            'jours_disponibles'    => 'required|array|min:1',
            'horaires_disponibles' => 'required|string|max:255',
            'date_debut_souhaitee' => 'required|date|after_or_equal:today',
            'adresse_cours'        => 'required|string|max:255',

            // Complémentaire
            'informations_complementaires' => 'nullable|string|max:2000',
        ]);

        // Convertir le texte en tableau (une matière par ligne)
        $matieres = array_values(array_filter(array_map('trim', explode("\n", $data['matieres_text']))));

        // Créer l'élève (sans le champ niveau)
        $eleve = Eleve::create([
            'parent_id'     => auth()->id(),
            'nom'           => $data['eleve_nom'],
            'prenom'        => $data['eleve_prenom'],
            'age'           => $data['eleve_age'] ?? null,
            'classe'        => $data['eleve_classe'],
            'etablissement' => $data['eleve_etablissement'] ?? null,
            // 'niveau' → retiré du formulaire, reste null en base
        ]);

        // Créer la demande
        $demande = Demande::create([
            'parent_id'                    => auth()->id(),
            'eleve_id'                     => $eleve->id,
            'matieres'                     => $matieres,
            'objectif'                     => $data['objectif'],
            'difficultes'                  => $data['difficultes'] ?? null,
            'preparation_examen'           => $data['preparation_examen'] ?? null,
            'seances_semaine'              => $data['seances_semaine'],
            'duree_seance'                 => $data['duree_seance'],
            'jours_disponibles'            => $data['jours_disponibles'],
            'horaires_disponibles'         => $data['horaires_disponibles'],
            'date_debut_souhaitee'         => $data['date_debut_souhaitee'],
            'adresse_cours'                => $data['adresse_cours'],
            'informations_complementaires' => $data['informations_complementaires'] ?? null,
            'statut'                       => 'demande_envoyee',
        ]);

        return redirect()->route('parent.demandes.show', $demande)
            ->with('success', 'Votre demande a été envoyée à MON MAÎTRE.');
    }

    public function show(Demande $demande)
    {
        if ($demande->parent_id !== auth()->id()) {
            abort(403);
        }

        return view('parent.demandes.show', compact('demande'));
    }
}