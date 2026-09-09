<?php

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;

class ReservationController
{
    // Injection des dépendances
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly CreerReservationService $creerReservationService,
        private readonly AnnulerReservationService $annulerReservationService,
        private readonly ReservationValidator $reservationValidator
    ) {
    }

    // Afficher la liste des réservations
    public function index(): void
    {
        $salleId = isset($_GET['salle_id']) && ctype_digit((string) $_GET['salle_id'])
            ? (int) $_GET['salle_id']
            : null;

        $reservations = $this->reservationRepository->lister($salleId);
        $salles = $this->salleRepository->lister();

        require __DIR__ . '/../../templates/reservation/index.php';
    }

    // Afficher une réservation
    public function show(int $id): void
    {
        $reservation = $this->reservationRepository->retrouver($id);

        if ($reservation === null) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        require __DIR__ . '/../../templates/reservation/show.php';
    }

    // Afficher le formulaire de création
    public function create(): void
    {
        $salles = $this->salleRepository->lister();

        require __DIR__ . '/../../templates/reservation/form.php';
    }

    // Enregistrer une nouvelle réservation
    public function store(): void
    {
        // Récupérer les données du formulaire
        $data = $_POST;

        // Valider les données
        $result = $this->reservationValidator->validate($data);

        // Si les données sont invalides
        if (!$result->isValid()) {
            $errors = $result->errors();

            $salles = $this->salleRepository->lister();

            // Réafficher le formulaire avec les erreurs
            require __DIR__ . '/../../templates/reservation/form.php';
            return;
        }

        // Convertir les dates en objets DateTimeImmutable
        $dateDebut = new \DateTimeImmutable($data['date_debut']);
        $dateFin = new \DateTimeImmutable($data['date_fin']);

        // Créer le DTO
        $dto = new CreerReservationDTO(
            salleId: (int) $data['salle_id'],
            responsable: $data['responsable'],
            email: $data['email'],
            motif: $data['motif'],
            dateDebut: $dateDebut,
            dateFin: $dateFin
        );

        try {
            $this->creerReservationService->executer($dto);
        } catch (SalleIndisponibleException|\InvalidArgumentException $exception) {
            $errors = ['general' => [$exception->getMessage()]];
            $salles = $this->salleRepository->lister();

            require __DIR__ . '/../../templates/reservation/form.php';
            return;
        }

        // Rediriger vers la liste
        header('Location: /reservations?success=reservation_created');
        exit;
    }

    // Annuler une réservation
    public function cancel(int $id): void
    {
        try {
            $this->annulerReservationService->executer($id);
        } catch (ReservationIntrouvableException) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        // Rediriger vers la liste
        header('Location: /reservations?success=reservation_cancelled');
        exit;
    }
}
