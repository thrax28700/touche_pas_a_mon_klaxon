<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Repositories\AgenceRepository;
use Tests\Support\DatabaseTestCase;

final class AgenceRepositoryTest extends DatabaseTestCase
{
    private AgenceRepository $agences;

    protected function setUp(): void
    {
        parent::setUp();
        $this->agences = new AgenceRepository($this->pdo);
    }

    public function testCreateAndFind(): void
    {
        $id = $this->agences->create('Angers');

        $agence = $this->agences->find($id);

        self::assertNotNull($agence);
        self::assertSame('Angers', $agence['nom']);
    }

    public function testFindByNom(): void
    {
        $this->agences->create('Brest');

        $agence = $this->agences->findByNom('Brest');

        self::assertNotNull($agence);
        self::assertSame('Brest', $agence['nom']);
        self::assertNull($this->agences->findByNom('Ville-Inexistante'));
    }

    public function testUpdate(): void
    {
        $id = $this->agences->create('Dijon');

        $this->agences->update($id, 'Dijon-Centre');

        $agence = $this->agences->find($id);
        self::assertSame('Dijon-Centre', $agence['nom']);
    }

    public function testDelete(): void
    {
        $id = $this->agences->create('Metz');

        $this->agences->delete($id);

        self::assertNull($this->agences->find($id));
    }

    public function testIsReferencedByTrajetIsFalseWhenUnused(): void
    {
        $id = $this->agences->create('Caen');

        self::assertFalse($this->agences->isReferencedByTrajet($id));
    }

    public function testIsReferencedByTrajetIsTrueWhenUsed(): void
    {
        $depart = $this->agences->create('Orleans');
        $arrivee = $this->agences->create('Tours');

        $userId = $this->createUser();

        $statement = $this->pdo->prepare(
            'INSERT INTO trajets (agence_depart_id, agence_arrivee_id, date_heure_depart, date_heure_arrivee, nb_places_total, nb_places_disponibles, utilisateur_id)
             VALUES (:depart, :arrivee, :d1, :d2, 3, 3, :utilisateur)'
        );
        $statement->execute([
            'depart' => $depart,
            'arrivee' => $arrivee,
            'd1' => (new \DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
            'd2' => (new \DateTimeImmutable('+1 day +2 hours'))->format('Y-m-d H:i:s'),
            'utilisateur' => $userId,
        ]);

        self::assertTrue($this->agences->isReferencedByTrajet($depart));
        self::assertTrue($this->agences->isReferencedByTrajet($arrivee));
    }

    private function createUser(): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO utilisateurs (nom, prenom, telephone, email, mot_de_passe, role)
             VALUES (:nom, :prenom, :telephone, :email, :mot_de_passe, :role)'
        );
        $statement->execute([
            'nom' => 'Test',
            'prenom' => 'Utilisateur',
            'telephone' => '0600000000',
            'email' => uniqid('test_', true) . '@example.test',
            'mot_de_passe' => password_hash('secret', PASSWORD_BCRYPT),
            'role' => 'employe',
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
