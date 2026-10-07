<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isParent();
    }

    public function rules(): array
    {
        return [
            // Élève
            'eleve_nom'           => 'required|string|max:100',
            'eleve_prenom'        => 'required|string|max:100',
            'eleve_age'           => 'nullable|integer|min:3|max:30',
            'eleve_classe'        => 'required|string|max:50',
            'eleve_etablissement' => 'nullable|string|max:150',

            // Besoin pédagogique
            'matieres_text'      => 'required|string',
            'objectif'           => 'required|string|max:1000',
            'difficultes'        => 'nullable|string|max:1000',
            'preparation_examen' => 'nullable|string|max:100',

            // Organisation
            'seances_semaine'      => 'required|integer|min:1|max:14',
            'duree_seance'         => 'required|integer|min:30|max:240',
            'jours_disponibles'    => 'required|array|min:1',
            'horaires_disponibles' => 'required|string|max:255',
            'date_debut_souhaitee' => 'required|date|after_or_equal:today',
            'adresse_cours'        => 'required|string|max:255',

            // Complémentaire
            'informations_complementaires' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'eleve_nom.required'            => 'Le nom de l\'élève est obligatoire.',
            'eleve_prenom.required'         => 'Le prénom de l\'élève est obligatoire.',
            'eleve_classe.required'         => 'La classe de l\'élève est obligatoire.',
            'matieres_text.required'        => 'Veuillez indiquer au moins une matière.',
            'objectif.required'             => 'L\'objectif est obligatoire.',
            'jours_disponibles.required'    => 'Veuillez cocher au moins un jour.',
            'date_debut_souhaitee.required' => 'La date de début est obligatoire.',
            'date_debut_souhaitee.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
        ];
    }
}