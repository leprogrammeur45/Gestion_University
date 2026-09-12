<?php

namespace App\Service;

use App\Exception\SalleIntrouvableException;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;

final class TrouverSalleService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository
    ) {
    }

    public function executer(int $id): Salle
    {
        $salle = $this->salleRepository->retrouver($id);

        if ($salle === null) {
            throw new SalleIntrouvableException(
                'La salle demandée est introuvable.'
            );
        }

        return $salle;
    }
}
