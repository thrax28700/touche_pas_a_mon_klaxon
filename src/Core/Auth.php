<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\UtilisateurRepository;

/**
 * Authentification basée sur la session PHP (email + mot de passe haché).
 *
 * @phpstan-import-type UtilisateurRow from UtilisateurRepository
 */
final class Auth
{
    private const SESSION_KEY = 'utilisateur_id';

    public function __construct(private readonly UtilisateurRepository $utilisateurs)
    {
    }

    /**
     * Tente une connexion. Régénère l'identifiant de session en cas de succès
     * pour se prémunir d'une fixation de session.
     */
    public function attempt(string $email, string $motDePasse): bool
    {
        $utilisateur = $this->utilisateurs->findByEmail($email);

        if ($utilisateur === null || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = $utilisateur['id'];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /**
     * @return UtilisateurRow|null
     */
    public function user(): ?array
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return null;
        }

        return $this->utilisateurs->find((int) $_SESSION[self::SESSION_KEY]);
    }

    public function id(): ?int
    {
        return isset($_SESSION[self::SESSION_KEY]) ? (int) $_SESSION[self::SESSION_KEY] : null;
    }

    public function isAdmin(): bool
    {
        $utilisateur = $this->user();

        return $utilisateur !== null && $utilisateur['role'] === 'admin';
    }
}
