<?php

declare(strict_types=1);

namespace App\Support;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthHelper
{
    /**
     * Vérifie que l'utilisateur est propriétaire de la ressource.
     * Lance une 403 sinon.
     */
    public static function verifierPropriete($model, ?int $userId = null, string $champ = 'parent_id'): void
    {
        $userId = $userId ?? auth()->id();

        if ($userId === null) {
            throw new HttpException(403, 'Authentification requise.');
        }

        if ((int) $model->{$champ} !== (int) $userId) {
            throw new HttpException(403, 'Accès refusé.');
        }
    }
}