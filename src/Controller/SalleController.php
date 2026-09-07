<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreerSalleService;
use App\Validation\SalleValidator;

class SalleController
{
    // Injection des dépendances
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly CreerSalleService $creerSalleService,
        private readonly SalleValidator $salleValidator
    ) {
    }

    // Afficher la liste des salles
    public function index(): void
    {
        $salles = $this->salleRepository->lister();

        require __DIR__ . '/../../templates/salle/index.php';
    }

    // Afficher une salle
    public function show(int $id): void
    {
        $salle = $this->salleRepository->retrouver($id);

        require __DIR__ . '/../../templates/salle/show.php';
    }

    // Afficher le formulaire de création
    public function create(): void
    {
        require __DIR__ . '/../../templates/salle/form.php';
    }

    // Enregistrer une nouvelle salle
    public function store(): void
    {
        // Récupérer les données du formulaire
        $data = $_POST;

        // Valider les données
        $result = $this->salleValidator->validate($data);

        // Si les données sont invalides
        if (!$result->isValid()) {
            $errors = $result->errors();

            // Réafficher le formulaire avec les erreurs
            require __DIR__ . '/../../templates/salle/form.php';
            return;
        }

        // Créer le DTO
        $dto = new CreerSalleDTO(
            nom: $data['nom'],
            batiment: $data['batiment'],
            capacite: (int) $data['capacite'],
            type: $data['type'],
            active: (bool) $data['active']
        );

        // Créer la salle
        $this->creerSalleService->executer($dto);

        // Rediriger vers la liste
        header('Location: /salles');
        exit;
    }

    // Afficher le formulaire de modification
    public function edit(int $id): void
    {
    // Récupérer la salle
    $salle = $this->salleRepository->retrouver($id);

    // Afficher le formulaire
    require __DIR__ . '/../../templates/salle/form.php';
    }

    // Modifier une salle
    public function update(int $id): void
    {
    // Récupérer la salle
    $salle = $this->salleRepository->retrouver($id);

    // Récupérer les données du formulaire
    $data = $_POST;

    // Valider les données
    $result = $this->salleValidator->validate($data);

    // Si les données sont invalides
    if (!$result->isValid()) {
        $errors = $result->errors();

        // Réafficher le formulaire
        require __DIR__ . '/../../templates/salle/form.php';
        return;
    }

    // Modifier les informations de la salle
    $salle->nom = $data['nom'];
    $salle->batiment = $data['batiment'];
    $salle->capacite = (int) $data['capacite'];
    $salle->type = $data['type'];
    $salle->active = (bool) $data['active'];

    // Enregistrer les modifications
    $this->salleRepository->enregistrer($salle);

    // Rediriger vers la liste des salles
    header('Location: /salles');
    exit;
    }
}
