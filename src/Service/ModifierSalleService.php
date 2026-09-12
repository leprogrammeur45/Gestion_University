<?php

namespace App\Service;

use App\DTO\CreerSalleDTO;
use App\Exception\SalleIntrouvableException;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;

final class ModifierSalleService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository
    ) {
    }

    public function executer(int $id, CreerSalleDTO $dto): Salle
    {
        $salle = $this->salleRepository->retrouver($id);

        if ($salle === null) {
            throw new SalleIntrouvableException(
                'La salle demandée est introuvable.'
            );
        }

        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type;
        $salle->active = $dto->active;

        return $this->salleRepository->enregistrer($salle);
    }
}
