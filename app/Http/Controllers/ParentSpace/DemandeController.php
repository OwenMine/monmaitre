<?php

declare(strict_types=1);

namespace App\Http\Controllers\ParentSpace;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDemandeRequest;
use App\Models\Demande;
use App\Models\Eleve;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DemandeController extends Controller
{
    /**
     * Liste des demandes du parent connecté.
     */
    public function index(): View
    {
        $demandes = Demande::where('parent_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('parent.demandes.index', compact('demandes'));
    }

    /**
     * Formulaire de création d'une nouvelle demande.
     */
    public function create(): View
    {
        return view('parent.demandes.create');
    }

    /**
     * Enregistre une nouvelle demande + crée l'élève associé.
     */
    public function store(StoreDemandeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // 1. Créer l'élève
        $eleve = $this->creerEleve($data);

        // 2. Créer la demande
        $demande = $this->creerDemande($data, $eleve);

        return redirect()->route('parent.demandes.show', $demande)
            ->with('success', 'Votre demande a été envoyée à MON MAÎTRE.');
    }

    /**
     * Détails d'une demande (avec vérification d'ownership).
     */
    public function show(Demande $demande): View
    {
        if ($demande->parent_id !== auth()->id()) {
            abort(403);
        }

        return view('parent.demandes.show', compact('demande'));
    }

    // ============================================================
    // Méthodes privées (D.8)
    // ============================================================

    /**
     * Crée un élève à partir des données du formulaire.
     */
    private function creerEleve(array $data): Eleve
    {
        return Eleve::create([
            'parent_id'     => auth()->id(),
            'nom'           => $data['eleve_nom'],
            'prenom'        => $data['eleve_prenom'],
            'age'           => $data['eleve_age'] ?? null,
            'classe'        => $data['eleve_classe'],
            'etablissement' => $data['eleve_etablissement'] ?? null,
        ]);
    }

    /**
     * Crée une demande à partir des données du formulaire et de l'élève.
     */
    private function creerDemande(array $data, Eleve $eleve): Demande
    {
        // Convertir les matières (une par ligne) en tableau
        $matieres = array_values(array_filter(
            array_map('trim', explode("\n", $data['matieres_text']))
        ));

        return Demande::create([
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
    }
}