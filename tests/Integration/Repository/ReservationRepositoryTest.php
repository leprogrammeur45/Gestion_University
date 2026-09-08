<?php

namespace Tests\Integration\Repository;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepository;
use DateTimeImmutable;
use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;

class ReservationRepositoryTest extends TestCase
{
    private ReservationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Charger la configuration Eloquent
        require __DIR__ . '/../../../config/database.php';

        // Démarrer une transaction
        Capsule::connection()->beginTransaction();

        $this->repository = new ReservationRepository();
    }

    protected function tearDown(): void
    {
        // Annuler les modifications effectuées pendant le test
        Capsule::connection()->rollBack();

        parent::tearDown();
    }

    public function testRetrouveUneReservationExistante(): void
    {
        // Créer une salle de test
        $salle = Salle::create([
            'nom' => 'Salle Test',
            'batiment' => 'Bâtiment Test',
            'capacite' => 30,
            'type' => 'cours',
            'active' => true,
        ]);

        // Créer une réservation de test
        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Test PHPUnit',
            'email' => 'test@example.com',
            'motif' => 'Test du repository',
            'date_debut' => new DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new DateTimeImmutable('+1 day 12:00'),
            'statut' => 'confirmee',
        ]);

        // Utiliser le repository réel
        $result = $this->repository->retrouver($reservation->id);

        // Vérifications
        $this->assertNotNull($result);
        $this->assertSame($reservation->id, $result->id);
        $this->assertSame('Test PHPUnit', $result->responsable);
    }
}