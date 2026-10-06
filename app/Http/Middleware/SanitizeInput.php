<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Champs à NE PAS nettoyer (mots de passe, emails, etc.)
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        '_token',
        '_method',
        'email',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            // Ignorer les champs exclus
            if (in_array($key, $this->except)) {
                return;
            }

            // Ne nettoyer que les chaînes
            if (! is_string($value)) {
                return;
            }

            // Retirer toutes les balises HTML et PHP
            $value = strip_tags($value);

            // Retirer les caractères de contrôle invisibles (sauf \n, \r, \t)
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);

            // Trim
            $value = trim($value);
        });

        $request->merge($input);

        return $next($request);
    }
}