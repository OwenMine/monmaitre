<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasStatutLabel;
use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    use HasStatutLabel;

    protected $fillable = [
        'demande_id', 'repetiteur_id', 'statut', 'message',
        'date_proposition', 'date_reponse', 'raison_refus',
    ];

    protected $casts = [
        'date_proposition' => 'datetime',
        'date_reponse'     => 'datetime',
    ];

    // ============================================
    // Relations
    // ============================================

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function repetiteur()
    {
        return $this->belongsTo(Repetiteur::class);
    }

    // ============================================
    // Trait HasStatutLabel
    // ============================================

    protected function statutLabels(): array
    {
        return [
            'proposee' => 'En attente de réponse',
            'acceptee' => 'Acceptée par le parent',
            'refusee'  => 'Refusée par le parent',
            'annulee'  => 'Annulée',
        ];
    }

    protected function statutCouleurs(): array
    {
        return [
            'proposee' => 'bg-mm-accent text-mm-navy',
            'acceptee' => 'bg-green-100 text-green-700',
            'refusee'  => 'bg-red-100 text-red-700',
            'annulee'  => 'bg-gray-100 text-gray-700',
        ];
    }
}