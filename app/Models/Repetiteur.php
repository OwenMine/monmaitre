<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasStatutLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Profil complet d'un répétiteur.
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $photo
 * @property string|null $niveau_etudes
 * @property string|null $diplome
 * @property string|null $etablissement
 * @property int|null $annee_obtention
 * @property array|null $matieres
 * @property array|null $classes
 * @property string|null $niveau_min
 * @property string|null $niveau_max
 * @property int $experience_annees
 * @property array|null $jours_disponibles
 * @property string|null $horaires_disponibles
 * @property int $max_eleves
 * @property array|null $communes
 * @property array|null $quartiers
 * @property int $rayon_deplacement
 * @property string $statut
 *
 * @method int placements_count
 * @method int evaluations_count
 */
class Repetiteur extends Model
{
    use HasStatutLabel;

    // ============================================
    // Properties
    // ============================================

    protected $fillable = [
        'user_id', 'photo',
        'niveau_etudes', 'diplome', 'etablissement', 'annee_obtention',
        'matieres', 'classes', 'niveau_min', 'niveau_max',
        'experience_annees', 'experience_repetition', 'experiences_particulieres', 'references',
        'jours_disponibles', 'horaires_disponibles', 'max_eleves',
        'communes', 'quartiers', 'rayon_deplacement',
        'statut',
    ];

    protected $casts = [
        'matieres'          => 'array',
        'classes'           => 'array',
        'jours_disponibles' => 'array',
        'communes'          => 'array',
        'quartiers'         => 'array',
    ];

    // ============================================
    // Accessors
    // ============================================

    /**
     * URL publique de la photo de profil (via route dédiée).
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (! Storage::disk('public')->exists($this->photo)) {
            return null;
        }

        return route('repetiteur.photo', $this);
    }

    /**
     * Initiale du nom du répétiteur (fallback quand pas de photo).
     */
    public function getInitialeAttribute(): string
    {
        return strtoupper(substr($this->user->name ?? 'M', 0, 1));
    }

    // ============================================
    // Relations
    // ============================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function propositions(): HasMany
    {
        return $this->hasMany(Proposition::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    // ============================================
    // Trait HasStatutLabel
    // ============================================

    protected function statutLabels(): array
    {
        return [
            'pre_inscrit'           => 'Pré-inscrit',
            'dossier_incomplet'     => 'Dossier incomplet',
            'candidature_envoyee'   => 'Candidature envoyée',
            'a_examiner'            => 'À examiner',
            'entretien_a_effectuer' => 'Entretien à effectuer',
            'documents_a_verifier'  => 'Documents à vérifier',
            'profil_valide'         => 'Profil validé',
            'profil_en_attente'     => 'Profil en attente',
            'profil_refuse'         => 'Profil refusé',
            'profil_suspendu'       => 'Profil suspendu',
        ];
    }

    protected function statutCouleurs(): array
    {
        return [
            'pre_inscrit'           => 'bg-gray-100 text-gray-700',
            'dossier_incomplet'     => 'bg-gray-100 text-gray-700',
            'candidature_envoyee'   => 'bg-mm-accent/30 text-mm-navy',
            'a_examiner'            => 'bg-mm-accent/30 text-mm-navy',
            'entretien_a_effectuer' => 'bg-mm-navy/10 text-mm-navy',
            'documents_a_verifier'  => 'bg-mm-navy/10 text-mm-navy',
            'profil_valide'         => 'bg-green-100 text-green-800',
            'profil_en_attente'     => 'bg-orange-100 text-orange-700',
            'profil_refuse'         => 'bg-red-100 text-red-800',
            'profil_suspendu'       => 'bg-red-100 text-red-800',
        ];
    }
}