<?php

namespace App\Controller;

use App\DTO\CreerReservationDTOBuilder;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Service\ListerReservationsService;
use App\Service\ListerSallesService;
use App\Service\TrouverReservationService;
use App\Validation\ReservationValidator;

class ReservationController
{
    public function __construct(
        private readonly ListerReservationsService $listerReservationsService,
        private readonly TrouverReservationService $trouverReservationService,
        private readonly ListerSallesService $listerSallesService,
        private readonly CreerReservationService $creerReservationService,
        private readonly AnnulerReservationService $annulerReservationService,
        private readonly ReservationValidator $reservationValidator,
        private readonly CreerReservationDTOBuilder $reservationDTOBuilder
    ) {
    }
//affiche la liste des reservations
    public function index(): void
    {
        $salleId = isset($_GET['salle_id']) && ctype_digit((string) $_GET['salle_id'])
            ? (int) $_GET['salle_id']
            : null;

        $reservations = $this->listerReservationsService->executer($salleId);
        $salles = $this->listerSallesService->executer();

        require __DIR__ . '/../../templates/reservation/index.php';
    }
//affiche une reservation
    public function show(int $id): void
    {
        try {
            $reservation = $this->trouverReservationService->executer($id);
        } catch (ReservationIntrouvableException) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }

        require __DIR__ . '/../../templates/reservation/show.php';
    }
//affiche le formulaire
    public function create(): void
    {
        $salles = $this->listerSallesService->executer();

        require __DIR__ . '/../../templates/reservation/form.php';
    }
//traite la creation d une reservation
    public function store(): void
    {
        $data = $_POST;

        $result = $this->reservationValidator->validate($data);

        if (!$result->isValid()) {
            $errors = $result->errors();
            $salles = $this->listerSallesService->executer();

            require __DIR__ . '/../../templates/reservation/form.php';
            return;
        }

        $dateDebut = new \DateTimeImmutable($data['date_debut']);
        $dateFin = new \DateTimeImmutable($data['date_fin']);

        $dto = $this->reservationDTOBuilder
            ->salleId((int) $data['salle_id'])
            ->responsable($data['responsable'])
            ->email($data['email'])
            ->motif($data['motif'])
            ->dateDebut($dateDebut)
            ->dateFin($dateFin)
            ->build();

        try {
            $this->creerReservationService->executer($dto);
        } catch (SalleIndisponibleException|\InvalidArgumentException $exception) {
            $errors = ['general' => [$exception->getMessage()]];
            $salles = $this->listerSallesService->executer();

            require __DIR__ . '/../../templates/reservation/form.php';
            return;
        }

        header('Location: /reservations?success=reservation_created');
        exit;
    }
//annule une reservation
    public function cancel(int $id): void
    {
        try {
            $this->annulerReservationService->executer($id);
        } catch (ReservationIntrouvableException) {
            http_response_code(404);
            require __DIR__ . '/../../templates/error/404.php';
            return;
        }  
        header('Location: /reservations?success=reservation_cancelled');
        exit;

      
    }
}