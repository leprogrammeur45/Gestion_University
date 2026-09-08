<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

class CreerReservationService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }

    /** Crée une réservation après vérification des règles métier. */
    public function executer(CreerReservationDTO $dto): Reservation
    {
        // 1. Retrouver la salle
        $salle = $this->salleRepository->retrouver($dto->salleId);

        // 2. Vérifier que la salle est active
        if ($salle === null || !$salle->active) {
            throw new SalleIndisponibleException(
                'La salle demandée est inexistante ou inactive.'
            );
        }

        // 3. Vérifier que le début précède la fin
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new \InvalidArgumentException(
                'La date de début doit précéder la date de fin.'
            );
        }

        // 4. Vérifier que la durée ne dépasse pas quatre heures
        $duree = $dto->dateFin->getTimestamp()
            - $dto->dateDebut->getTimestamp();

        if ($duree > 4 * 60 * 60) {
            throw new \InvalidArgumentException(
                'La durée de réservation ne peut pas dépasser quatre heures.'
            );
        }

        // 5. Vérifier que la date est future
        if ($dto->dateDebut <= new \DateTimeImmutable()) {
            throw new \InvalidArgumentException(
                'La date de début doit être dans le futur.'
            );
        }

        // 6. Rechercher les chevauchements
        $conflit = $this->reservationRepository->rechercherConflit(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin
        );

        if ($conflit !== null) {
            throw new SalleIndisponibleException(
                'La salle est déjà réservée sur cette période.'
            );
        }

        // 7. Créer la réservation
        $reservation = new Reservation();

        $reservation->salle_id = $dto->salleId;
        $reservation->responsable = $dto->responsable;
        $reservation->email = $dto->email;
        $reservation->motif = $dto->motif;
        $reservation->date_debut = $dto->dateDebut;
        $reservation->date_fin = $dto->dateFin;

        // Valeur technique utilisée par MySQL
        $reservation->statut = 'confirmee';

        // 8. Enregistrer la réservation
        $reservation = $this->reservationRepository->enregistrer($reservation);

        // 9. Retourner le résultat
        return $reservation;
    }
}