<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'placement_id', 'parent_id', 'repetiteur_id',
        'note', 'commentaire',
    ];

    protected $casts = [
        'note' => 'integer',
    ];

    // ============================================
    // Relations
    // ============================================

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function repetiteur()
    {
        return $this->belongsTo(Repetiteur::class);
    }

    // ============================================
    // Helpers
    // ============================================

    /**
     * Couleur de la note pour l'affichage.
     */
    public function noteCouleur(): string
    {
        if ($this->note >= 8) return 'text-green-600';
        if ($this->note >= 6) return 'text-mm-secondary';
        if ($this->note >= 4) return 'text-orange-500';
        return 'text-red-600';
    }

    /**
     * Label textuel de la note.
     */
    public function noteLabel(): string
    {
        if ($this->note >= 9) return 'Excellent';
        if ($this->note >= 7) return 'Très bien';
        if ($this->note >= 5) return 'Bien';
        if ($this->note >= 3) return 'Moyen';
        return 'Insuffisant';
    }
}