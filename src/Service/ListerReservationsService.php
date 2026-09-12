<?php

namespace App\Service;

use App\Repository\ReservationRepositoryInterface;

final class ListerReservationsService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function executer(?int $salleId = null): array
    {
        return $this->reservationRepository->lister($salleId);
    }
}
