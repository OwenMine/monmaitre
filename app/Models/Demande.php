<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'parent_id', 'eleve_id', 'matieres', 'objectif', 'difficultes',
        'preparation_examen', 'seances_semaine', 'duree_seance',
        'jours_disponibles', 'horaires_disponibles', 'date_debut_souhaitee',
        'adresse_cours', 'informations_complementaires', 'statut',
    ];

    protected $casts = [
        'matieres' => 'array',
        'jours_disponibles' => 'array',
        'date_debut_souhaitee' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'dossier_incomplet' => 'Dossier incomplet',
            'dossier_complet' => 'Dossier complet',
            'demande_envoyee' => 'Demande envoyée',
            'en_analyse' => 'En analyse',
            'recherche_repetiteur' => 'Recherche de répétiteur',
            'repetiteur_identifie' => 'Répétiteur identifié',
            'repetiteur_contacte' => 'Répétiteur contacté',
            'attente_confirmation_parent' => 'Attente de confirmation',
            'placement_confirme' => 'Placement confirmé',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'annule' => 'Annulé',
            'remplacement_demande' => 'Remplacement demandé',
            default => 'Inconnu',
        };
    }
        public function propositions()
    {
        return $this->hasMany(Proposition::class);
    }

    public function derniereProposition()
    {
        return $this->hasOne(Proposition::class)->latestOfMany();
    }
    public function placement()
{
    return $this->hasOne(Placement::class);
}
}