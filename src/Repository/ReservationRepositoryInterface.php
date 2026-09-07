<?php

namespace App\Repository;

use App\Model\Reservation;

interface ReservationRepositoryInterface
{
    public function lister(): array;

    public function retrouver(int $id): ?Reservation;

    public function rechercherConflit(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation;

    public function enregistrer(Reservation $reservation): Reservation;

    public function annuler(Reservation $reservation): Reservation;
}