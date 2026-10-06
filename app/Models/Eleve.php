<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = [
        'parent_id', 'nom', 'prenom', 'age',
        'classe', 'etablissement', 'niveau',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }
}