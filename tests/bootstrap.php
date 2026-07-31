<?php

declare(strict_types=1);

/**
 * Amorce de la suite PHPUnit : charge la configuration de test et (re)crée
 * le schéma dans une base MySQL dédiée aux tests, à partir de database/schema.sql.
 */

use App\Config\Env;

require dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env.testing');

$host = Env::get('DB_HOST', '127.0.0.1');
$port = Env::get('DB_PORT', '3306');
$database = Env::get('DB_DATABASE', 'touche_pas_au_klaxon_test');
$username = Env::get('DB_USERNAME', 'root');
$password = Env::get('DB_PASSWORD', '');

$pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec("DROP DATABASE IF EXISTS `{$database}`");
$pdo->exec("CREATE DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `{$database}`");

$schema = file_get_contents(dirname(__DIR__) . '/database/schema.sql');
// Le script de production cible la base "touche_pas_au_klaxon" : on retire ses
// propres CREATE DATABASE/USE pour appliquer uniquement les tables sur la base de test.
$schema = preg_replace('/CREATE DATABASE.*?;/is', '', $schema, 1);
$schema = preg_replace('/USE\s+touche_pas_au_klaxon\s*;/i', '', $schema, 1);

foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
    $pdo->exec($statement);
}
