<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasStatutLabel;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasStatutLabel;

    protected $fillable = [
        'placement_id', 'repetiteur_id',
        'date', 'duree_minutes', 'contenu', 'commentaire',
        'statut', 'validee_at', 'validee_par', 'raison_contestation',
    ];

    protected $casts = [
        'date'       => 'date',
        'validee_at' => 'datetime',
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

    // ============================================
    // Helpers métier
    // ============================================

    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function estValidee(): bool
    {
        return $this->statut === 'effectuee';
    }

    // ============================================
    // Trait HasStatutLabel
    // ============================================

    protected function statutLabels(): array
    {
        return [
            'en_attente'         => 'En attente de validation',
            'effectuee'          => 'Validée',
            'contested'          => 'Contestée',
            'absence_repetiteur' => 'Absence répétiteur',
            'absence_eleve'      => 'Absence élève',
            'annulee'            => 'Annulée',
        ];
    }

    protected function statutCouleurs(): array
    {
        return [
            'en_attente'         => 'bg-mm-accent/30 text-mm-navy',
            'effectuee'          => 'bg-green-100 text-green-700',
            'contested'          => 'bg-red-100 text-red-700',
            'absence_repetiteur' => 'bg-red-100 text-red-700',
            'absence_eleve'      => 'bg-orange-100 text-orange-700',
            'annulee'            => 'bg-gray-100 text-gray-700',
        ];
    }
}