<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Trait pour les modèles avec un champ `statut`.
 * Fournit des méthodes standardisées pour les labels et couleurs.
 */
trait HasStatutLabel
{
    /**
     * Retourne le label lisible du statut actuel.
     */
    public function statutLabel(): string
    {
        return $this->statutLabels()[$this->statut] ?? 'Inconnu';
    }

    /**
     * Retourne la classe CSS de couleur du statut actuel.
     */
    public function statutCouleur(): string
    {
        return $this->statutCouleurs()[$this->statut] ?? 'bg-gray-100 text-gray-700';
    }

    /**
     * Retourne le tableau des labels possibles.
     * À surcharger dans le modèle.
     */
    abstract protected function statutLabels(): array;

    /**
     * Retourne le tableau des couleurs possibles.
     * À surcharger dans le modèle.
     */
    abstract protected function statutCouleurs(): array;
}