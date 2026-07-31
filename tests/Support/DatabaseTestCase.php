<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Config\Database;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Classe de base pour les tests d'intégration touchant la base de test.
 * Chaque test s'exécute dans une transaction annulée à la fin, pour rester
 * isolé des autres tests sans avoir à réimporter le schéma à chaque fois.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo = Database::connection();
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }

        parent::tearDown();
    }
}
