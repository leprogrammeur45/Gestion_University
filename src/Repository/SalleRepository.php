<?php

namespace App\Repository;

use App\Model\Salle;

class SalleRepository implements SalleRepositoryInterface
{
    /** Accède aux salles avec Eloquent. */
    public function lister(): array
    {
        return Salle::query()->get()->all();
    }

    public function retrouver(int $id): ?Salle
    {
        return Salle::find($id);
    }

    public function enregistrer(Salle $salle): Salle
    {
        $salle->save();

        return $salle;
    }
}