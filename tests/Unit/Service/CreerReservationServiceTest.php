<?php
namespace Tests\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreerReservationService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CreerReservationServiceTest extends TestCase
{
    public function testCreeUneReservationPourUneSalleDisponible(): void
    {
        $salle = new Salle(['active' => true]);

        $salleRepository = $this->createMock(
            SalleRepositoryInterface::class
        );

        $salleRepository->expects($this->once())
            ->method('retrouver')
            ->willReturn($salle);

        $reservation = new Reservation();

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->once())
            ->method('rechercherConflit')
            ->willReturn(null);

        $reservationRepository->expects($this->once())
            ->method('enregistrer')
            ->willReturn($reservation);

        $dateDebut = new DateTimeImmutable('+1 day 10:00');
        $dateFin = $dateDebut->modify('+2 hours');

        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateFin
        );

        $result = (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);

        $this->assertSame($reservation, $result);
    }

    public function testRefuseUneSalleInactive(): void
    {
        $salle = new Salle(['active' => false]);

        $salleRepository = $this->createStub(
            SalleRepositoryInterface::class
        );

        $salleRepository->method('retrouver')
            ->willReturn($salle);

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->never())
            ->method('enregistrer');

        $dateDebut = new DateTimeImmutable('+1 day 10:00');

        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateDebut->modify('+2 hours')
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);
    }

    public function testRefuseUnChevauchement(): void
    {
        $salleRepository = $this->createStub(
            SalleRepositoryInterface::class
        );

        $salleRepository->method('retrouver')
            ->willReturn(
                new Salle(['active' => true])
            );

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->once())
            ->method('rechercherConflit')
            ->willReturn(new Reservation());

        $reservationRepository->expects($this->never())
            ->method('enregistrer');

        $dateDebut = new DateTimeImmutable('+1 day 10:00');

        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateDebut->modify('+2 hours')
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);
    }

    public function testRefuseUneSalleInexistante(): void
    {
        $salleRepository = $this->createStub(
            SalleRepositoryInterface::class
        );

        $salleRepository->method('retrouver')
            ->willReturn(null);

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->never())
            ->method('enregistrer');

        $dateDebut = new DateTimeImmutable('+1 day 10:00');

        $dto = new CreerReservationDTO(
            999,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateDebut->modify('+2 hours')
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);
    }

    public function testRefuseUneDateDeDebutApresLaDateDeFin(): void
    {
        $salleRepository = $this->createStub(
            SalleRepositoryInterface::class
        );

        $salleRepository->method('retrouver')
            ->willReturn(
                new Salle(['active' => true])
            );

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->never())
            ->method('rechercherConflit');

        $reservationRepository->expects($this->never())
            ->method('enregistrer');

        $dateDebut = new DateTimeImmutable('+1 day 12:00');
        $dateFin = new DateTimeImmutable('+1 day 10:00');

        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateFin
        );

        $this->expectException(
            \InvalidArgumentException::class
        );

        (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);
    }

    public function testRefuseUneDureeSuperieureAQuatreHeures(): void
    {
        $salleRepository = $this->createStub(
            SalleRepositoryInterface::class
        );

        $salleRepository->method('retrouver')
            ->willReturn(
                new Salle(['active' => true])
            );

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->never())
            ->method('rechercherConflit');

        $reservationRepository->expects($this->never())
            ->method('enregistrer');

        $dateDebut = new DateTimeImmutable('+1 day 10:00');
        $dateFin = $dateDebut->modify('+5 hours');

        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateFin
        );

        $this->expectException(
            \InvalidArgumentException::class
        );

        (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);
    }

    public function testRefuseUneDateDeDebutDansLePasse(): void
    {
        $salleRepository = $this->createStub(
            SalleRepositoryInterface::class
        );

        $salleRepository->method('retrouver')
            ->willReturn(
                new Salle(['active' => true])
            );

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );

        $reservationRepository->expects($this->never())
            ->method('rechercherConflit');

        $reservationRepository->expects($this->never())
            ->method('enregistrer');

        $dateDebut = new DateTimeImmutable('-1 day 10:00');
        $dateFin = $dateDebut->modify('+2 hours');

        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateFin
        );

        $this->expectException(
            \InvalidArgumentException::class
        );

        (new CreerReservationService(
            $salleRepository,
            $reservationRepository
        ))->executer($dto);
    }

    public function testAccepteUneReservationVoisineSansChevauchement(): void
    {
        $salleRepository = $this->createStub(SalleRepositoryInterface::class);
        $salleRepository->method('retrouver')
            ->willReturn(new Salle(['active' => true]));

        $reservationRepository = $this->createMock(
            ReservationRepositoryInterface::class
        );
        $reservationRepository->expects($this->once())
            ->method('rechercherConflit')
            ->willReturn(null);
        $reservationRepository->expects($this->once())
            ->method('enregistrer')
            ->willReturn(new Reservation());

        $dateDebut = new DateTimeImmutable('+1 day 12:00');
        $dto = new CreerReservationDTO(
            1,
            'Mamadou Diouf',
            'mamadou@example.com',
            'Réunion pédagogique',
            $dateDebut,
            $dateDebut->modify('+2 hours')
        );

        $this->assertInstanceOf(
            Reservation::class,
            (new CreerReservationService(
                $salleRepository,
                $reservationRepository
            ))->executer($dto)
        );
    }
}

