<?php

declare(strict_types=1);

/**
 * Script d'alimentation de la base avec le jeu d'essai fourni en annexe
 * (agences.txt, users.txt). À exécuter après avoir créé le schéma :
 *   php database/seed.php
 *
 * Tous les comptes importés partagent le même mot de passe par défaut
 * (à communiquer aux utilisateurs de test). Le premier utilisateur du
 * fichier est désigné administrateur, les 19 autres sont des employés.
 */

use App\Config\Database;
use App\Config\Env;

require dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');

const DEFAULT_PASSWORD = 'Password123!';
const ADMIN_EMAIL = 'alexandre.martin@email.fr';

$root = dirname(__DIR__);
$agencesFile = $root . '/database/jeu-d-essai/agences.txt';
$usersFile = $root . '/database/jeu-d-essai/users.txt';

$pdo = Database::connection();

// Les instructions DDL (TRUNCATE) provoquent un COMMIT implicite en MySQL/MariaDB :
// elles doivent donc être exécutées avant l'ouverture de la transaction d'import.
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE trajets');
$pdo->exec('TRUNCATE TABLE utilisateurs');
$pdo->exec('TRUNCATE TABLE agences');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$pdo->beginTransaction();

try {
    $agenceStatement = $pdo->prepare('INSERT INTO agences (nom) VALUES (:nom)');
    foreach (file($agencesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $ville) {
        $agenceStatement->execute(['nom' => trim($ville)]);
    }

    $motDePasseHash = password_hash(DEFAULT_PASSWORD, PASSWORD_BCRYPT);

    $userStatement = $pdo->prepare(
        'INSERT INTO utilisateurs (nom, prenom, telephone, email, mot_de_passe, role)
         VALUES (:nom, :prenom, :telephone, :email, :mot_de_passe, :role)'
    );

    foreach (file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        [$nom, $prenom, $telephone, $email] = str_getcsv($line);
        $role = strcasecmp($email, ADMIN_EMAIL) === 0 ? 'admin' : 'employe';

        $userStatement->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'email' => $email,
            'mot_de_passe' => $motDePasseHash,
            'role' => $role,
        ]);
    }

    $pdo->commit();

    echo "Jeu d'essai importé avec succès." . PHP_EOL;
    echo 'Mot de passe commun à tous les comptes : ' . DEFAULT_PASSWORD . PHP_EOL;
    echo 'Compte administrateur : ' . ADMIN_EMAIL . PHP_EOL;
} catch (Throwable $exception) {
    $pdo->rollBack();
    fwrite(STDERR, 'Échec du seed : ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
