<?php
//Le DTO sert à transporter toutes les informations 
//nécessaires pour créer une réservation dans un seul objet.
namespace App\DTO;

use DateTimeImmutable;

final class CreerReservationDTO
{
    public function __construct(
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly DateTimeImmutable $dateDebut,
        public readonly DateTimeImmutable $dateFin
    ) {
    }
}
//readonly
//Une fois que la valeur est définie, on ne peut plus la modifier.