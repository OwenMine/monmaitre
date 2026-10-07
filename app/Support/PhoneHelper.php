<?php

namespace App\Support;

class PhoneHelper
{
    /**
     * Détecte si une chaîne est un email valide.
     */
    public static function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Normalise un numéro de téléphone ivoirien.
     *
     * Exemples :
     *   "07 19 16 76 88"      → "0719167688"
     *   "+225 07 19 16 76 88" → "0719167688"
     *   "2250719167688"       → "0719167688"
     */
    public static function normalize(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value);

        if (str_starts_with($digits, '225')) {
            $digits = substr($digits, 3);
        }

        return $digits;
    }

    /**
     * Formate un numéro pour l'affichage.
     *
     * "0719167688" → "07 19 16 76 88"
     */
    public static function format(string $value): string
    {
        $digits = self::normalize($value);

        if (strlen($digits) !== 10) {
            return $value;
        }

        return implode(' ', str_split($digits, 2));
    }
}