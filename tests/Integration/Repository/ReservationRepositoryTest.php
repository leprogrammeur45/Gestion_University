<?php

namespace Tests\Integration\Repository;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
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

    public function testCreeUneSalleAvecEloquent(): void
    {
        $repository = new SalleRepository();

        $salle = $repository->enregistrer(new Salle([
            'nom' => 'Salle Integration',
            'batiment' => 'Bâtiment I',
            'capacite' => 20,
            'type' => 'reunion',
            'active' => true,
        ]));

        $this->assertNotNull($salle->id);
        $this->assertSame('Salle Integration', $salle->nom);
    }

    public function testUneSallePossedeSesReservations(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Relation',
            'batiment' => 'Bâtiment R',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Test Relation',
            'email' => 'relation@example.com',
            'motif' => 'Test relation Eloquent',
            'date_debut' => new DateTimeImmutable('+2 days 10:00'),
            'date_fin' => new DateTimeImmutable('+2 days 12:00'),
            'statut' => 'confirmee',
        ]);

        $this->assertTrue($salle->reservations->contains($reservation));
        $this->assertSame($salle->id, $reservation->salle->id);
    }

    public function testRechercheUnChevauchementConfirme(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Conflit',
            'batiment' => 'Bâtiment C',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);

        Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Test Conflit',
            'email' => 'conflit@example.com',
            'motif' => 'Test recherche conflit',
            'date_debut' => new DateTimeImmutable('+3 days 10:00'),
            'date_fin' => new DateTimeImmutable('+3 days 12:00'),
            'statut' => 'confirmee',
        ]);

        $conflit = $this->repository->rechercherConflit(
            $salle->id,
            new DateTimeImmutable('+3 days 11:00'),
            new DateTimeImmutable('+3 days 13:00')
        );

        $this->assertNotNull($conflit);
    }

    public function testAnnuleUneReservationEtLaRetireDesConflits(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Annulation',
            'batiment' => 'Bâtiment A',
            'capacite' => 20,
            'type' => 'reunion',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Test Annulation',
            'email' => 'annulation@example.com',
            'motif' => 'Test annulation réservation',
            'date_debut' => new DateTimeImmutable('+4 days 10:00'),
            'date_fin' => new DateTimeImmutable('+4 days 12:00'),
            'statut' => 'confirmee',
        ]);

        $this->repository->annuler($reservation);

        $conflit = $this->repository->rechercherConflit(
            $salle->id,
            new DateTimeImmutable('+4 days 11:00'),
            new DateTimeImmutable('+4 days 13:00')
        );

        $this->assertSame('annulee', $reservation->statut);
        $this->assertNull($conflit);
    }

    public function testFiltreLesReservationsParSalle(): void
    {
        $salleIncluse = Salle::create([
            'nom' => 'Salle Filtre A',
            'batiment' => 'Bâtiment F',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);
        $salleExclue = Salle::create([
            'nom' => 'Salle Filtre B',
            'batiment' => 'Bâtiment F',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);

        foreach ([$salleIncluse, $salleExclue] as $salle) {
            Reservation::create([
                'salle_id' => $salle->id,
                'responsable' => 'Test Filtre',
                'email' => 'filtre@example.com',
                'motif' => 'Test filtrage salle',
                'date_debut' => new DateTimeImmutable('+5 days 10:00'),
                'date_fin' => new DateTimeImmutable('+5 days 12:00'),
                'statut' => 'confirmee',
            ]);
        }

        $reservations = $this->repository->lister($salleIncluse->id);

        $this->assertCount(1, $reservations);
        $this->assertSame($salleIncluse->id, $reservations[0]->salle_id);
    }
}