<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Levée lorsqu'une saisie utilisateur ne passe pas la validation métier.
 */
final class ValidationException extends RuntimeException
{
    /**
     * @param array<string, string> $errors Erreurs indexées par nom de champ.
     */
    public function __construct(private readonly array $errors, string $message = 'Données invalides.')
    {
        parent::__construct($message);
    }

    /**
     * @return array<string, string>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
