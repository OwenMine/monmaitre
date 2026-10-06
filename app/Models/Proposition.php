<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    protected $fillable = [
        'demande_id', 'repetiteur_id', 'statut', 'message',
        'date_proposition', 'date_reponse', 'raison_refus',
    ];

    protected $casts = [
        'date_proposition' => 'datetime',
        'date_reponse' => 'datetime',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function repetiteur()
    {
        return $this->belongsTo(Repetiteur::class);
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'proposee' => 'En attente de réponse',
            'acceptee' => 'Acceptée par le parent',
            'refusee' => 'Refusée par le parent',
            'annulee' => 'Annulée',
            default => 'Inconnu',
        };
    }

    public function statutCouleur(): string
    {
        return match ($this->statut) {
            'acceptee' => 'bg-green-100 text-green-700',
            'refusee' => 'bg-red-100 text-red-700',
            'annulee' => 'bg-gray-100 text-gray-700',
            default => 'bg-[#E7C273] text-[#132D39]',
        };
    }
}