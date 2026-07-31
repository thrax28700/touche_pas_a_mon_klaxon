<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Database;
use App\Repositories\AgenceRepository;
use App\Repositories\TrajetRepository;
use App\Repositories\UtilisateurRepository;

/**
 * Petit localisateur de services. Le routeur instancie les contrôleurs
 * sans pouvoir leur injecter de dépendances (`new $class()`), cette classe
 * sert donc de point d'accès unique aux services partagés (BDD, repositories,
 * authentification).
 */
final class App
{
    private static ?Auth $auth = null;
    private static ?AgenceRepository $agences = null;
    private static ?UtilisateurRepository $utilisateurs = null;
    private static ?TrajetRepository $trajets = null;

    private function __construct()
    {
    }

    public static function auth(): Auth
    {
        return self::$auth ??= new Auth(self::utilisateurs());
    }

    public static function agences(): AgenceRepository
    {
        return self::$agences ??= new AgenceRepository(Database::connection());
    }

    public static function utilisateurs(): UtilisateurRepository
    {
        return self::$utilisateurs ??= new UtilisateurRepository(Database::connection());
    }

    public static function trajets(): TrajetRepository
    {
        return self::$trajets ??= new TrajetRepository(Database::connection());
    }
}
