<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Chargeur minimaliste de fichier .env (sans dépendance externe).
 */
final class Env
{
    private static bool $loaded = false;

    /**
     * Charge le fichier .env indiqué dans les variables d'environnement PHP
     * (getenv) si ce n'est pas déjà fait.
     */
    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (is_file($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }

                if (!str_contains($line, '=')) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                $value = trim($value, "\"'");

                if (getenv($name) === false) {
                    putenv("{$name}={$value}");
                    $_ENV[$name] = $value;
                }
            }
        }

        self::$loaded = true;
    }

    /**
     * Récupère une variable d'environnement avec une valeur par défaut.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);

        return $value === false ? $default : $value;
    }
}
