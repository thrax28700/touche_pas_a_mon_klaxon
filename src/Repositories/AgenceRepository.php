<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * Accès aux agences (villes). Seul l'administrateur peut créer, modifier
 * ou supprimer une agence.
 *
 * @phpstan-type AgenceRow array{id: int, nom: string}
 */
final class AgenceRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<AgenceRow>
     */
    public function all(): array
    {
        $statement = $this->pdo->query('SELECT * FROM agences ORDER BY nom');

        return $statement->fetchAll();
    }

    /**
     * @return AgenceRow|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM agences WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return AgenceRow|null
     */
    public function findByNom(string $nom): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM agences WHERE nom = :nom');
        $statement->execute(['nom' => $nom]);

        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    public function create(string $nom): int
    {
        $statement = $this->pdo->prepare('INSERT INTO agences (nom) VALUES (:nom)');
        $statement->execute(['nom' => $nom]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, string $nom): void
    {
        $statement = $this->pdo->prepare('UPDATE agences SET nom = :nom WHERE id = :id');
        $statement->execute(['nom' => $nom, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM agences WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    /**
     * Indique si l'agence est référencée par au moins un trajet
     * (empêche une suppression qui casserait l'intégrité référentielle).
     */
    public function isReferencedByTrajet(int $id): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM trajets WHERE agence_depart_id = :id1 OR agence_arrivee_id = :id2'
        );
        $statement->execute(['id1' => $id, 'id2' => $id]);

        return (int) $statement->fetchColumn() > 0;
    }
}
