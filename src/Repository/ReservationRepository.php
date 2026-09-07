<?php

namespace App\Repository;

use App\Model\Reservation;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function lister(): array
    {
        return Reservation::query()->get()->all();
    }

    public function retrouver(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function rechercherConflit(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation
    {
        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->first();
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
{
    $reservation->statut = 'annulée';
    $reservation->save();

    return $reservation;
}
}