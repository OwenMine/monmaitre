<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'repetiteur_id', 'type', 'nom_fichier', 'chemin',
    ];

    public function repetiteur()
    {
        return $this->belongsTo(Repetiteur::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'cv' => 'CV',
            'diplome' => 'Diplôme',
            'certificat' => 'Certificat',
            'autre' => 'Autre',
            default => 'Document',
        };
    }
}