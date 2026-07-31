<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * Accès en lecture aux utilisateurs (comptes importés depuis le SIRH,
 * aucune création/modification/suppression n'est prévue par l'application).
 *
 * @phpstan-type UtilisateurRow array{id: int, nom: string, prenom: string, telephone: string, email: string, mot_de_passe: string, role: string}
 */
final class UtilisateurRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return UtilisateurRow|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return UtilisateurRow|null
     */
    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $statement->execute(['email' => $email]);

        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return list<UtilisateurRow>
     */
    public function all(): array
    {
        $statement = $this->pdo->query('SELECT * FROM utilisateurs ORDER BY nom, prenom');

        return $statement->fetchAll();
    }

    /**
     * Utilisé uniquement par le script de seed pour importer le jeu d'essai.
     */
    public function create(string $nom, string $prenom, string $telephone, string $email, string $motDePasseHash, string $role): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO utilisateurs (nom, prenom, telephone, email, mot_de_passe, role)
             VALUES (:nom, :prenom, :telephone, :email, :mot_de_passe, :role)'
        );

        $statement->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'email' => $email,
            'mot_de_passe' => $motDePasseHash,
            'role' => $role,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
