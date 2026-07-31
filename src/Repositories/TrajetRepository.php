<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

/**
 * Accès aux trajets proposés par les employés. Les méthodes de liste
 * renvoient des lignes déjà jointes aux agences et à l'auteur, prêtes
 * pour l'affichage.
 *
 * @phpstan-type TrajetRow array{
 *     id: int,
 *     agence_depart_id: int,
 *     agence_depart_nom: string,
 *     agence_arrivee_id: int,
 *     agence_arrivee_nom: string,
 *     date_heure_depart: string,
 *     date_heure_arrivee: string,
 *     nb_places_total: int,
 *     nb_places_disponibles: int,
 *     utilisateur_id: int,
 *     auteur_nom: string,
 *     auteur_prenom: string,
 *     auteur_telephone: string,
 *     auteur_email: string
 * }
 */
final class TrajetRepository
{
    private const SELECT_JOIN = <<<'SQL'
        SELECT
            t.id,
            t.agence_depart_id,
            ad.nom AS agence_depart_nom,
            t.agence_arrivee_id,
            aa.nom AS agence_arrivee_nom,
            t.date_heure_depart,
            t.date_heure_arrivee,
            t.nb_places_total,
            t.nb_places_disponibles,
            t.utilisateur_id,
            u.nom AS auteur_nom,
            u.prenom AS auteur_prenom,
            u.telephone AS auteur_telephone,
            u.email AS auteur_email
        FROM trajets t
        INNER JOIN agences ad ON ad.id = t.agence_depart_id
        INNER JOIN agences aa ON aa.id = t.agence_arrivee_id
        INNER JOIN utilisateurs u ON u.id = t.utilisateur_id
        SQL;

    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Trajets à venir avec au moins une place disponible, triés par
     * date de départ croissante (page d'accueil).
     *
     * @return list<TrajetRow>
     */
    public function findAvailableUpcoming(): array
    {
        $statement = $this->pdo->query(
            self::SELECT_JOIN . '
            WHERE t.date_heure_depart > NOW() AND t.nb_places_disponibles > 0
            ORDER BY t.date_heure_depart ASC'
        );

        return $statement->fetchAll();
    }

    /**
     * Tous les trajets, sans filtre (tableau de bord admin).
     *
     * @return list<TrajetRow>
     */
    public function all(): array
    {
        $statement = $this->pdo->query(self::SELECT_JOIN . ' ORDER BY t.date_heure_depart ASC');

        return $statement->fetchAll();
    }

    /**
     * @return TrajetRow|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(self::SELECT_JOIN . ' WHERE t.id = :id');
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array{
     *     agence_depart_id: int,
     *     agence_arrivee_id: int,
     *     date_heure_depart: string,
     *     date_heure_arrivee: string,
     *     nb_places_total: int,
     *     utilisateur_id: int
     * } $data
     */
    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO trajets
                (agence_depart_id, agence_arrivee_id, date_heure_depart, date_heure_arrivee,
                 nb_places_total, nb_places_disponibles, utilisateur_id)
             VALUES
                (:agence_depart_id, :agence_arrivee_id, :date_heure_depart, :date_heure_arrivee,
                 :nb_places_total, :nb_places_disponibles, :utilisateur_id)'
        );

        $statement->execute([
            'agence_depart_id' => $data['agence_depart_id'],
            'agence_arrivee_id' => $data['agence_arrivee_id'],
            'date_heure_depart' => $data['date_heure_depart'],
            'date_heure_arrivee' => $data['date_heure_arrivee'],
            'nb_places_total' => $data['nb_places_total'],
            'nb_places_disponibles' => $data['nb_places_total'],
            'utilisateur_id' => $data['utilisateur_id'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array{
     *     agence_depart_id: int,
     *     agence_arrivee_id: int,
     *     date_heure_depart: string,
     *     date_heure_arrivee: string,
     *     nb_places_total: int,
     *     nb_places_disponibles: int
     * } $data
     */
    public function update(int $id, array $data): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE trajets SET
                agence_depart_id = :agence_depart_id,
                agence_arrivee_id = :agence_arrivee_id,
                date_heure_depart = :date_heure_depart,
                date_heure_arrivee = :date_heure_arrivee,
                nb_places_total = :nb_places_total,
                nb_places_disponibles = :nb_places_disponibles
             WHERE id = :id'
        );

        $statement->execute([
            'agence_depart_id' => $data['agence_depart_id'],
            'agence_arrivee_id' => $data['agence_arrivee_id'],
            'date_heure_depart' => $data['date_heure_depart'],
            'date_heure_arrivee' => $data['date_heure_arrivee'],
            'nb_places_total' => $data['nb_places_total'],
            'nb_places_disponibles' => $data['nb_places_disponibles'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM trajets WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}
