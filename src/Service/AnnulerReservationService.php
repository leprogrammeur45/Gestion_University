<?php

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

class AnnulerReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function executer(int $id): Reservation
    {
        // 1. Retrouver la réservation
        $reservation = $this->reservationRepository->retrouver($id);

        // 2. Vérifier que la réservation existe
        if ($reservation === null) {
            throw new ReservationIntrouvableException(
                'La réservation demandée est introuvable.'
            );
        }

        // 3. Annuler la réservation
        $reservation = $this->reservationRepository->annuler($reservation);

        // 4. Retourner la réservation
        return $reservation;
    }
}