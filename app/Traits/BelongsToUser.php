<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait pour les modèles qui appartiennent à un utilisateur.
 * Fournit un helper pour vérifier la propriété.
 */
trait BelongsToUser
{
    /**
     * Vérifie si le modèle appartient à l'utilisateur donné.
     */
    public function appartientA(?int $userId): bool
    {
        if ($userId === null) {
            return false;
        }

        // Déterminer le champ de propriété (parent_id par défaut)
        $champ = property_exists($this, 'champ_proprietaire')
            ? $this->champ_proprietaire
            : 'parent_id';

        return (int) $this->{$champ} === (int) $userId;
    }

    /**
     * Scope : filtrer par propriétaire.
     */
    public function scopePourUtilisateur(Builder $query, int $userId): Builder
    {
        $champ = property_exists($this, 'champ_proprietaire')
            ? $this->champ_proprietaire
            : 'parent_id';

        return $query->where($champ, $userId);
    }
}