<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasStatutLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Placement confirmé entre un parent et un répétiteur.
 *
 * @property int $id
 * @property string $reference
 * @property int $demande_id
 * @property int $proposition_id
 * @property int $parent_id
 * @property int $repetiteur_id
 * @property int $eleve_id
 * @property string $matiere
 * @property string $adresse
 * @property \Carbon\Carbon $date_debut
 * @property string|null $frequence
 * @property string|null $horaires
 * @property float|null $tarif_convenu
 * @property string $statut
 * @property string|null $commentaires
 * @property \Carbon\Carbon|null $date_fin
 *
 * @method int seances_count
 * @method int seances_effectuees_count
 * @method int seances_absence_count
 * @method int seances_total_count
 */
class Placement extends Model
{
    use HasStatutLabel;

    // ============================================
    // Properties
    // ============================================

    protected $fillable = [
        'reference', 'demande_id', 'proposition_id',
        'parent_id', 'repetiteur_id', 'eleve_id',
        'matiere', 'adresse', 'date_debut', 'frequence',
        'horaires', 'tarif_convenu', 'statut',
        'commentaires', 'date_fin',
    ];

    protected $casts = [
        'date_debut'    => 'date',
        'date_fin'      => 'datetime',
        'tarif_convenu' => 'decimal:2',
    ];

    // ============================================
    // Relations
    // ============================================

    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class);
    }

    public function proposition(): BelongsTo
    {
        return $this->belongsTo(Proposition::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function repetiteur(): BelongsTo
    {
        return $this->belongsTo(Repetiteur::class);
    }

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Statistiques complètes sur les séances du placement.
     */
    public function getStatistiquesSeancesAttribute(): array
    {
        $seances = $this->relationLoaded('seances')
            ? $this->seances
            : $this->seances()->get();

        $effectuees = $seances->where('statut', 'effectuee')->count();
        $absences   = $seances->where('statut', 'absence_repetiteur')->count();
        $enAttente  = $seances->where('statut', 'en_attente')->count();
        $total      = $seances->count();
        $minutes    = $seances->where('statut', 'effectuee')->sum('duree_minutes');

        return [
            'effectuees' => $effectuees,
            'absences'   => $absences,
            'en_attente' => $enAttente,
            'total'      => $total,
            'heures'     => round($minutes / 60, 1),
            'taux'       => $total > 0 ? round(($effectuees / $total) * 100, 1) : 0.0,
        ];
    }

    // ============================================
    // Méthodes métier
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
            return 0.0;
        }

        $effectuees = $this->seances()->where('statut', 'effectuee')->count();

        return round(($effectuees / $total) * 100, 1);
    }

    public function peutEtreEvalue(): bool
    {
        return in_array($this->statut, ['termine', 'evaluation']) && ! $this->evaluation;
    }

    public function noteMoyenne(): ?int
    {
        return $this->evaluation?->note;
    }

    // ============================================
    // Trait HasStatutLabel
    // ============================================

    protected function statutLabels(): array
    {
        return [
            'en_attente'   => 'En attente',
            'confirme'     => 'Confirmé',
            'en_cours'     => 'En cours',
            'evaluation'   => 'En évaluation',
            'termine'      => 'Terminé',
            'remplacement' => 'Remplacement demandé',
            'annule'       => 'Annulé',
        ];
    }

    protected function statutCouleurs(): array
    {
        return [
            'en_attente'   => 'bg-mm-navy/10 text-mm-navy',
            'confirme'     => 'bg-mm-accent text-mm-navy',
            'en_cours'     => 'bg-green-100 text-green-700',
            'evaluation'   => 'bg-blue-100 text-blue-700',
            'termine'      => 'bg-gray-100 text-gray-700',
            'remplacement' => 'bg-orange-100 text-orange-700',
            'annule'       => 'bg-red-100 text-red-700',
        ];
    }

    // ============================================
    // Static
    // ============================================

    /**
     * Génère une référence unique au format MM-YYYY-XXXX.
     *
     * En cas de collision (rare mais possible), une boucle de sécurité
     * incrémente le numéro jusqu'à trouver une référence libre.
     */
    public static function genererReference(): string
    {
        $annee = date('Y');
        $prefix = 'MM-' . $annee . '-';

        $derniereReference = static::where('reference', 'like', $prefix . '%')
            ->orderByDesc('reference')
            ->value('reference');

        $numero = $derniereReference
            ? (int) substr($derniereReference, -4) + 1
            : 1;

        do {
            $reference = $prefix . str_pad((string) $numero, 4, '0', STR_PAD_LEFT);
            $existe = static::where('reference', $reference)->exists();
            $numero++;
        } while ($existe);

        return $reference;
    }
}