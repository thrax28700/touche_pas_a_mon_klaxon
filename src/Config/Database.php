<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Fournit une connexion PDO unique (singleton) vers la base MySQL/MariaDB.
 */
final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    /**
     * Retourne la connexion PDO partagée, en la créant si nécessaire.
     */
    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $host = Env::get('DB_HOST', '127.0.0.1');
            $port = Env::get('DB_PORT', '3306');
            $database = Env::get('DB_DATABASE', 'touche_pas_au_klaxon');
            $username = Env::get('DB_USERNAME', 'root');
            $password = Env::get('DB_PASSWORD', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $exception) {
                throw new RuntimeException(
                    'Impossible de se connecter à la base de données : ' . $exception->getMessage(),
                    0,
                    $exception
                );
            }
        }

        return self::$connection;
    }

    /**
     * Permet d'injecter une connexion (utilisé par les tests).
     */
    public static function setConnection(PDO $connection): void
    {
        self::$connection = $connection;
    }
}
