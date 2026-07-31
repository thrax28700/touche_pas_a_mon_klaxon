<?php

declare(strict_types=1);

namespace App\Validation;

/**
 * Règles de validation pour la création/modification d'une agence (ville).
 */
final class AgenceValidator
{
    /**
     * @param array{nom?: string|null} $input
     *
     * @return array<string, string> Erreurs indexées par champ, vide si valide.
     */
    public static function validate(array $input): array
    {
        $errors = [];
        $nom = trim((string) ($input['nom'] ?? ''));

        if ($nom === '') {
            $errors['nom'] = "Le nom de l'agence est obligatoire.";
        } elseif (mb_strlen($nom) > 100) {
            $errors['nom'] = "Le nom de l'agence ne doit pas dépasser 100 caractères.";
        }

        return $errors;
    }
}
