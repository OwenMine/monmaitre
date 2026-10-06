<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    protected $fillable = [
        'reference', 'demande_id', 'proposition_id',
        'parent_id', 'repetiteur_id', 'eleve_id',
        'matiere', 'adresse', 'date_debut', 'frequence',
        'horaires', 'tarif_convenu', 'statut',
        'commentaires', 'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'datetime',
        'tarif_convenu' => 'decimal:2',
    ];

    // Relations
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function proposition()
    {
        return $this->belongsTo(Proposition::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function repetiteur()
    {
        return $this->belongsTo(Repetiteur::class);
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

        public function seances()
    {
        return $this->hasMany(Seance::class);
    }

        public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }

    /**
     * Vérifie si le placement peut être évalué.
     */
    public function peutEtreEvalue(): bool
    {
        return in_array($this->statut, ['termine', 'evaluation']) && ! $this->evaluation;
    }

    /**
     * Note moyenne reçue (si évalué).
     */
    public function noteMoyenne(): ?int
    {
        return $this->evaluation?->note;
    }

    // ============================================
    // Statistiques de suivi
    // ============================================

    public function nombreSeancesEffectuees(): int
    {
        return $this->seances()->where('statut', 'effectuee')->count();
    }

    public function nombreAbsencesRepetiteur(): int
    {
        return $this->seances()->where('statut', 'absence_repetiteur')->count();
    }

    public function totalMinutesEffectuees(): int
    {
        return (int) $this->seances()->where('statut', 'effectuee')->sum('duree_minutes');
    }

    public function tauxPresenceRepetiteur(): float
    {
        $total = $this->seances()->count();
        if ($total === 0) {
            return 0;
        }

        $effectuees = $this->seances()->where('statut', 'effectuee')->count();

        return round(($effectuees / $total) * 100, 1);
    }

    // Labels
    public function statutLabel(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'confirme' => 'Confirmé',
            'en_cours' => 'En cours',
            'evaluation' => 'En évaluation',
            'termine' => 'Terminé',
            'remplacement' => 'Remplacement demandé',
            'annule' => 'Annulé',
            default => 'Inconnu',
        };
    }

    public function statutCouleur(): string
    {
        return match ($this->statut) {
            'confirme' => 'bg-[#E7C273] text-[#132D39]',
            'en_cours' => 'bg-green-100 text-green-700',
            'termine' => 'bg-gray-100 text-gray-700',
            'annule' => 'bg-red-100 text-red-700',
            'remplacement' => 'bg-orange-100 text-orange-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    // Génération de la référence unique
    public static function genererReference(): string
    {
        $annee = date('Y');
        $dernier = static::whereYear('created_at', $annee)->count() + 1;

        return 'MM-' . $annee . '-' . str_pad($dernier, 4, '0', STR_PAD_LEFT);
    }
}