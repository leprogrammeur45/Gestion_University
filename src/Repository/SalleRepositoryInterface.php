<?php

namespace App\Repository;

use App\Model\Salle;

interface SalleRepositoryInterface
{
    public function lister(): array;

    public function retrouver(int $id): ?Salle;

    public function enregistrer(Salle $salle): Salle;
}