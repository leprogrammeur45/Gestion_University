<?php

namespace Tests\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Service\AnnulerReservationService;
use PHPUnit\Framework\TestCase;

class AnnulerReservationServiceTest extends TestCase
{
    public function testAnnuleUneReservationExistante(): void
    {
        // Réservation existante
        $reservation = new Reservation();

        // Mock du repository
        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        // Le repository doit retrouver la réservation
        $reservationRepository->expects($this->once())
            ->method('retrouver')
            ->with(1)
            ->willReturn($reservation);

        // Le repository doit annuler la réservation
        $reservationRepository->expects($this->once())
            ->method('annuler')
            ->with($reservation)
            ->willReturn($reservation);

        // Création du service
        $service = new AnnulerReservationService(
            $reservationRepository
        );

        // Exécution
        $result = $service->executer(1);

        // Vérification
        $this->assertSame($reservation, $result);
    }

    public function testRefuseUneReservationInexistante(): void
    {
        // Mock du repository
        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        // Le repository ne trouve aucune réservation
        $reservationRepository->expects($this->once())
            ->method('retrouver')
            ->with(999)
            ->willReturn(null);

        // annuler() ne doit surtout pas être appelé
        $reservationRepository->expects($this->never())
            ->method('annuler');

        // Création du service
        $service = new AnnulerReservationService(
            $reservationRepository
        );

        // Le service doit lancer cette exception
        $this->expectException(
            ReservationIntrouvableException::class
        );

        // Exécution
        $service->executer(999);
    }
}
