<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Génération et vérification de jetons CSRF stockés en session.
 */
final class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    /**
     * Retourne le jeton courant, en le créant s'il n'existe pas encore.
     */
    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Vérifie qu'un jeton soumis correspond au jeton en session.
     */
    public static function isValid(?string $token): bool
    {
        if (!is_string($token) || $token === '' || empty($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }

    /**
     * Génère le champ input caché à inclure dans les formulaires.
     */
    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}
