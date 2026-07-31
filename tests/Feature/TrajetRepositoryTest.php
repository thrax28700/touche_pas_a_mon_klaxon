<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Repositories\AgenceRepository;
use App\Repositories\TrajetRepository;
use App\Repositories\UtilisateurRepository;
use DateTimeImmutable;
use Tests\Support\DatabaseTestCase;

final class TrajetRepositoryTest extends DatabaseTestCase
{
    private TrajetRepository $trajets;
    private AgenceRepository $agences;
    private UtilisateurRepository $utilisateurs;
    private int $paris;
    private int $lyon;
    private int $auteurId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trajets = new TrajetRepository($this->pdo);
        $this->agences = new AgenceRepository($this->pdo);
        $this->utilisateurs = new UtilisateurRepository($this->pdo);

        $this->paris = $this->agences->create('Paris-Test');
        $this->lyon = $this->agences->create('Lyon-Test');
        $this->auteurId = $this->utilisateurs->create(
            'Dupont',
            'Jean',
            '0600000000',
            uniqid('jean.dupont_', true) . '@example.test',
            password_hash('secret', PASSWORD_BCRYPT),
            'employe'
        );
    }

    public function testCreateSetsPlacesDisponiblesEqualToTotal(): void
    {
        $id = $this->trajets->create([
            'agence_depart_id' => $this->paris,
            'agence_arrivee_id' => $this->lyon,
            'date_heure_depart' => (new DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
            'date_heure_arrivee' => (new DateTimeImmutable('+1 day +4 hours'))->format('Y-m-d H:i:s'),
            'nb_places_total' => 4,
            'utilisateur_id' => $this->auteurId,
        ]);

        $trajet = $this->trajets->find($id);

        self::assertNotNull($trajet);
        self::assertSame(4, (int) $trajet['nb_places_total']);
        self::assertSame(4, (int) $trajet['nb_places_disponibles']);
        self::assertSame('Paris-Test', $trajet['agence_depart_nom']);
        self::assertSame('Lyon-Test', $trajet['agence_arrivee_nom']);
    }

    public function testUpdateChangesPlacesDisponibles(): void
    {
        $id = $this->createTrajet();

        $this->trajets->update($id, [
            'agence_depart_id' => $this->paris,
            'agence_arrivee_id' => $this->lyon,
            'date_heure_depart' => (new DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
            'date_heure_arrivee' => (new DateTimeImmutable('+1 day +4 hours'))->format('Y-m-d H:i:s'),
            'nb_places_total' => 4,
            'nb_places_disponibles' => 1,
        ]);

        $trajet = $this->trajets->find($id);
        self::assertSame(1, (int) $trajet['nb_places_disponibles']);
    }

    public function testDelete(): void
    {
        $id = $this->createTrajet();

        $this->trajets->delete($id);

        self::assertNull($this->trajets->find($id));
    }

    public function testFindAvailableUpcomingExcludesFullTrajets(): void
    {
        $idAvecPlaces = $this->createTrajet(nbPlacesTotal: 3);
        $idComplet = $this->createTrajet(nbPlacesTotal: 2);

        // On vide artificiellement les places du second trajet.
        $this->trajets->update($idComplet, [
            'agence_depart_id' => $this->paris,
            'agence_arrivee_id' => $this->lyon,
            'date_heure_depart' => (new DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
            'date_heure_arrivee' => (new DateTimeImmutable('+1 day +4 hours'))->format('Y-m-d H:i:s'),
            'nb_places_total' => 2,
            'nb_places_disponibles' => 0,
        ]);

        $ids = array_column($this->trajets->findAvailableUpcoming(), 'id');

        self::assertContains($idAvecPlaces, $ids);
        self::assertNotContains($idComplet, $ids);
    }

    public function testFindAvailableUpcomingExcludesPastTrajets(): void
    {
        $idFutur = $this->createTrajet();

        $idPasse = $this->createTrajet();
        // Un trajet passé ne peut pas être créé via l'INSERT contraint par le
        // schéma (date_heure_arrivee > date_heure_depart uniquement) : on le
        // force directement en base pour le test.
        $this->pdo->prepare('UPDATE trajets SET date_heure_depart = :d, date_heure_arrivee = :a WHERE id = :id')
            ->execute([
                'd' => (new DateTimeImmutable('-2 days'))->format('Y-m-d H:i:s'),
                'a' => (new DateTimeImmutable('-2 days +2 hours'))->format('Y-m-d H:i:s'),
                'id' => $idPasse,
            ]);

        $ids = array_column($this->trajets->findAvailableUpcoming(), 'id');

        self::assertContains($idFutur, $ids);
        self::assertNotContains($idPasse, $ids);
    }

    private function createTrajet(int $nbPlacesTotal = 3): int
    {
        return $this->trajets->create([
            'agence_depart_id' => $this->paris,
            'agence_arrivee_id' => $this->lyon,
            'date_heure_depart' => (new DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
            'date_heure_arrivee' => (new DateTimeImmutable('+1 day +4 hours'))->format('Y-m-d H:i:s'),
            'nb_places_total' => $nbPlacesTotal,
            'utilisateur_id' => $this->auteurId,
        ]);
    }
}
