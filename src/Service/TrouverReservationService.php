<?php

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class TrouverReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function executer(int $id): Reservation
    {
        $reservation = $this->reservationRepository->retrouver($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException(
                'La réservation demandée est introuvable.'
            );
        }

        return $reservation;
    }
}
