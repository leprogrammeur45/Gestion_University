<?php

namespace App\Controller;

use App\DTO\CreerSalleDTOBuilder;
use App\Service\CreerSalleService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Service\TrouverSalleService;
use App\Validation\SalleValidator;

class SalleController
{
    public function __construct(
        private readonly ListerSallesService $listerSallesService,
        private readonly TrouverSalleService $trouverSalleService,
        private readonly CreerSalleService $creerSalleService,
        private readonly ModifierSalleService $modifierSalleService,
        private readonly SalleValidator $salleValidator,
        private readonly CreerSalleDTOBuilder $salleDTOBuilder
    ) {
    }
//afficher toutes les salles
    public function index(): void
    {
        $salles = $this->listerSallesService->executer();

        require __DIR__ . '/../../templates/salle/index.php';
    }
//affiche une salle précise
    public function show(int $id): void
    {
        try {
            $salle = $this->trouverSalleService->executer($id);
        } catch (\App\Exception\SalleIntrouvableException) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        require __DIR__ . '/../../templates/salle/show.php';
    }
//affiche seulement le formulaire
    public function create(): void
    {
        require __DIR__ . '/../../templates/salle/form.php';
    }
//récupère les données envoyées par le formulaire
    public function store(): void
    {
        $data = $_POST;

        $result = $this->salleValidator->validate($data);

        if (!$result->isValid()) {
            $errors = $result->errors();

            require __DIR__ . '/../../templates/salle/form.php';
            return;
        }

        $dto = $this->salleDTOBuilder
            ->nom($data['nom'])
            ->batiment($data['batiment'])
            ->capacite((int) $data['capacite'])
            ->type($data['type'])
            ->active(filter_var($data['active'], FILTER_VALIDATE_BOOLEAN))
            ->build();

        $this->creerSalleService->executer($dto);

        header('Location: /salles?success=salle_created');
        exit;
    }

    public function edit(int $id): void
    {
        try {
            $salle = $this->trouverSalleService->executer($id);
        } catch (\App\Exception\SalleIntrouvableException) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        require __DIR__ . '/../../templates/salle/form.php';
    }

    public function update(int $id): void
    {
        $data = $_POST;

        $result = $this->salleValidator->validate($data);

        if (!$result->isValid()) {
            $errors = $result->errors();

            try {
                $salle = $this->trouverSalleService->executer($id);
            } catch (\App\Exception\SalleIntrouvableException) {
                http_response_code(404);
                require __DIR__ . '/../../templates/error/404.php';
                return;
            }

            require __DIR__ . '/../../templates/salle/form.php';
            return;
        }

        $dto = $this->salleDTOBuilder
            ->nom($data['nom'])
            ->batiment($data['batiment'])
            ->capacite((int) $data['capacite'])
            ->type($data['type'])
            ->active(filter_var($data['active'], FILTER_VALIDATE_BOOLEAN))
            ->build();

        try {
            $this->modifierSalleService->executer($id, $dto);
        } catch (\App\Exception\SalleIntrouvableException) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        header('Location: /salles?success=salle_updated');
        exit;
    }
}