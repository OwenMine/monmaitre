<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Repetiteur extends Model
{
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
        'matieres' => 'array',
        'classes' => 'array',
        'jours_disponibles' => 'array',
        'communes' => 'array',
        'quartiers' => 'array',
    ];

    // ============================================
    // Accessor : URL publique de la photo
    // ============================================

        /**
     * Retourne l'URL de la photo via une route dédiée.
     * Contourne les problèmes de symlink storage.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (! \Storage::disk('public')->exists($this->photo)) {
            return null;
        }

        return route('repetiteur.photo', $this);
    }

    /**
     * Retourne l'initiale du user (fallback quand pas de photo).
     */
    public function getInitialeAttribute(): string
    {
        return strtoupper(substr($this->user->name ?? 'M', 0, 1));
    }

    // ============================================
    // Relations
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function propositions()
    {
        return $this->hasMany(Proposition::class);
    }

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
        public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    // ============================================
    // Labels statut
    // ============================================

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'pre_inscrit' => 'Pré-inscrit',
            'dossier_incomplet' => 'Dossier incomplet',
            'candidature_envoyee' => 'Candidature envoyée',
            'a_examiner' => 'À examiner',
            'entretien_a_effectuer' => 'Entretien à effectuer',
            'documents_a_verifier' => 'Documents à vérifier',
            'profil_valide' => 'Profil validé',
            'profil_en_attente' => 'Profil en attente',
            'profil_refuse' => 'Profil refusé',
            'profil_suspendu' => 'Profil suspendu',
            default => 'Inconnu',
        };
    }

    public function statutCouleur(): string
    {
        return match ($this->statut) {
            'profil_valide' => 'bg-green-100 text-green-800',
            'profil_refuse', 'profil_suspendu' => 'bg-red-100 text-red-800',
            'candidature_envoyee', 'a_examiner' => 'bg-blue-100 text-blue-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }
}