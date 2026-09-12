<?php

namespace App\Service;

use App\Repository\SalleRepositoryInterface;

final class ListerSallesService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository
    ) {
    }

    public function executer(): array
    {
        return $this->salleRepository->lister();
    }
}
