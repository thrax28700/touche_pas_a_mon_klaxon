<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Gère les messages flash (affichés une seule fois après redirection),
 * stockés en session.
 */
final class FlashMessage
{
    private const SESSION_KEY = 'flash_messages';

    /**
     * Enregistre un message flash pour la prochaine requête.
     *
     * @param string $type Type du message : 'success', 'error', 'info'.
     */
    public static function add(string $type, string $message): void
    {
        $_SESSION[self::SESSION_KEY][] = ['type' => $type, 'message' => $message];
    }

    /**
     * Récupère et vide la liste des messages flash en attente.
     *
     * @return array<int, array{type: string, message: string}>
     */
    public static function pull(): array
    {
        $messages = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);

        return $messages;
    }
}
