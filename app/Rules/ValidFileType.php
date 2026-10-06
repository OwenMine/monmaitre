<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ValidFileType implements ValidationRule
{
    protected array $allowedMimes;
    protected int $minSize;

    public function __construct(array $allowedMimes, int $minSize = 1024)
    {
        $this->allowedMimes = $allowedMimes;
        $this->minSize = $minSize;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('Le fichier est invalide.');
            return;
        }

        // Vérifier le VRAI MIME (analyse du contenu)
        $realMime = $value->getMimeType();

        if (! in_array($realMime, $this->allowedMimes)) {
            $fail("Le type de fichier réel n'est pas autorisé ({$realMime}).");
            return;
        }

        // Vérifier la taille minimale
        if ($value->getSize() < $this->minSize) {
            $fail('Le fichier semble vide ou corrompu.');
            return;
        }

        // Vérifier que le fichier est bien uploadé
        if (! $value->isValid()) {
            $fail("Le fichier n'a pas été correctement uploadé.");
            return;
        }
    }
}