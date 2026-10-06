<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $fillable = [
        'placement_id', 'repetiteur_id',
        'date', 'duree_minutes', 'contenu', 'commentaire',
        'statut', 'validee_at', 'validee_par', 'raison_contestation',
    ];

    protected $casts = [
        'date'        => 'date',
        'validee_at'  => 'datetime',
    ];

    // ============================================
    // Relations
    // ============================================

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    public function repetiteur()
    {
        return $this->belongsTo(Repetiteur::class);
    }

    public function valideePar()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    // ============================================
    // Labels & couleurs
    // ============================================

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'en_attente'         => 'En attente de validation',
            'effectuee'          => 'Validée',
            'contested'          => 'Contestée',
            'absence_repetiteur' => 'Absence répétiteur',
            'absence_eleve'      => 'Absence élève',
            'annulee'            => 'Annulée',
            default => 'Inconnu',
        };
    }

    public function statutCouleur(): string
    {
        return match ($this->statut) {
            'en_attente'         => 'bg-orange-100 text-orange-700',
            'effectuee'          => 'bg-green-100 text-green-700',
            'contested'          => 'bg-red-100 text-red-700',
            'absence_repetiteur' => 'bg-red-100 text-red-700',
            'absence_eleve'      => 'bg-orange-100 text-orange-700',
            'annulee'            => 'bg-gray-100 text-gray-700',
            default              => 'bg-gray-100 text-gray-700',
        };
    }

    // ============================================
    // Helpers
    // ============================================

    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function estValidee(): bool
    {
        return $this->statut === 'effectuee';
    }

    
}