<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasStatutLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Demande de répétiteur créée par un parent.
 *
 * @property int $id
 * @property int $parent_id
 * @property int $eleve_id
 * @property array|null $matieres
 * @property string $objectif
 * @property string|null $difficultes
 * @property string|null $preparation_examen
 * @property int $seances_semaine
 * @property int $duree_seance
 * @property array|null $jours_disponibles
 * @property string $horaires_disponibles
 * @property \Carbon\Carbon|null $date_debut_souhaitee
 * @property string $adresse_cours
 * @property string|null $informations_complementaires
 * @property string $statut
 *
 * @method int propositions_count
 */
class Demande extends Model
{
    use HasStatutLabel;

    // ============================================
    // Properties
    // ============================================

    protected $fillable = [
        'parent_id', 'eleve_id', 'matieres', 'objectif', 'difficultes',
        'preparation_examen', 'seances_semaine', 'duree_seance',
        'jours_disponibles', 'horaires_disponibles', 'date_debut_souhaitee',
        'adresse_cours', 'informations_complementaires', 'statut',
    ];

    protected $casts = [
        'matieres'             => 'array',
        'jours_disponibles'    => 'array',
        'date_debut_souhaitee' => 'date',
    ];

    // ============================================
    // Relations
    // ============================================

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function propositions(): HasMany
    {
        return $this->hasMany(Proposition::class);
    }

    public function derniereProposition(): HasOne
    {
        return $this->hasOne(Proposition::class)->latestOfMany();
    }

    public function placement(): HasOne
    {
        return $this->hasOne(Placement::class);
    }

    // ============================================
    // Trait HasStatutLabel
    // ============================================

    protected function statutLabels(): array
    {
        return [
            'dossier_incomplet'           => 'Dossier incomplet',
            'dossier_complet'             => 'Dossier complet',
            'demande_envoyee'             => 'Demande envoyée',
            'en_analyse'                  => 'En analyse',
            'recherche_repetiteur'        => 'Recherche de répétiteur',
            'repetiteur_identifie'        => 'Répétiteur identifié',
            'repetiteur_contacte'         => 'Répétiteur contacté',
            'attente_confirmation_parent' => 'Attente de confirmation',
            'placement_confirme'          => 'Placement confirmé',
            'en_cours'                    => 'En cours',
            'termine'                     => 'Terminé',
            'annule'                      => 'Annulé',
            'remplacement_demande'        => 'Remplacement demandé',
        ];
    }

    protected function statutCouleurs(): array
    {
        return [
            'dossier_incomplet'           => 'bg-gray-100 text-gray-700',
            'dossier_complet'             => 'bg-gray-100 text-gray-700',
            'demande_envoyee'             => 'bg-mm-accent text-mm-navy',
            'en_analyse'                  => 'bg-mm-navy text-white',
            'recherche_repetiteur'        => 'bg-mm-navy text-white',
            'repetiteur_identifie'        => 'bg-mm-navy text-white',
            'repetiteur_contacte'         => 'bg-mm-navy text-white',
            'attente_confirmation_parent' => 'bg-mm-secondary text-white',
            'placement_confirme'          => 'bg-green-100 text-green-700',
            'en_cours'                    => 'bg-green-100 text-green-700',
            'termine'                     => 'bg-gray-100 text-gray-700',
            'annule'                      => 'bg-red-100 text-red-700',
            'remplacement_demande'        => 'bg-orange-100 text-orange-700',
        ];
    }
}