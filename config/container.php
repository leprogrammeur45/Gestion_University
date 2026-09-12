<?php

use App\Application;

use App\Controller\ReservationController;
use App\Controller\SalleController;

use App\DTO\CreerReservationDTOBuilder;
use App\DTO\CreerSalleDTOBuilder;

use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;

use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Service\CreerSalleService;
use App\Service\ListerReservationsService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Service\TrouverReservationService;
use App\Service\TrouverSalleService;

use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

use Illuminate\Database\Capsule\Manager as Capsule;

use Psr\Container\ContainerInterface;

use function DI\factory;

return [
    SalleRepositoryInterface::class => factory(
        static function (Capsule $database): SalleRepositoryInterface {
            return new SalleRepository();
        }
    ),

    ReservationRepositoryInterface::class => factory(
        static function (Capsule $database): ReservationRepositoryInterface {
            return new ReservationRepository();
        }
    ),

    SalleValidator::class => factory(
        static fn (): SalleValidator => new SalleValidator()
    ),

    ReservationValidator::class => factory(
        static fn (): ReservationValidator => new ReservationValidator()
    ),

    CreerReservationDTOBuilder::class => factory(
        static fn (): CreerReservationDTOBuilder => new CreerReservationDTOBuilder()
    ),

    CreerSalleDTOBuilder::class => factory(
        static fn (): CreerSalleDTOBuilder => new CreerSalleDTOBuilder()
    ),

    ListerReservationsService::class => factory(
        static function (
            ReservationRepositoryInterface $reservationRepository
        ): ListerReservationsService {
            return new ListerReservationsService(
                $reservationRepository
            );
        }
    ),

    ListerSallesService::class => factory(
        static function (
            SalleRepositoryInterface $salleRepository
        ): ListerSallesService {
            return new ListerSallesService(
                $salleRepository
            );
        }
    ),

    TrouverReservationService::class => factory(
        static function (
            ReservationRepositoryInterface $reservationRepository
        ): TrouverReservationService {
            return new TrouverReservationService(
                $reservationRepository
            );
        }
    ),

    TrouverSalleService::class => factory(
        static function (
            SalleRepositoryInterface $salleRepository
        ): TrouverSalleService {
            return new TrouverSalleService(
                $salleRepository
            );
        }
    ),

    CreerReservationService::class => factory(
        static function (
            SalleRepositoryInterface $salleRepository,
            ReservationRepositoryInterface $reservationRepository
        ): CreerReservationService {
            return new CreerReservationService(
                $salleRepository,
                $reservationRepository
            );
        }
    ),

    AnnulerReservationService::class => factory(
        static function (
            ReservationRepositoryInterface $reservationRepository
        ): AnnulerReservationService {
            return new AnnulerReservationService(
                $reservationRepository
            );
        }
    ),

    CreerSalleService::class => factory(
        static function (
            SalleRepositoryInterface $salleRepository
        ): CreerSalleService {
            return new CreerSalleService(
                $salleRepository
            );
        }
    ),

    ModifierSalleService::class => factory(
        static function (
            SalleRepositoryInterface $salleRepository
        ): ModifierSalleService {
            return new ModifierSalleService(
                $salleRepository
            );
        }
    ),

    SalleController::class => factory(
        static function (
            ListerSallesService $listerSallesService,
            TrouverSalleService $trouverSalleService,
            CreerSalleService $creerSalleService,
            ModifierSalleService $modifierSalleService,
            SalleValidator $salleValidator,
            CreerSalleDTOBuilder $salleDTOBuilder
        ): SalleController {
            return new SalleController(
                $listerSallesService,
                $trouverSalleService,
                $creerSalleService,
                $modifierSalleService,
                $salleValidator,
                $salleDTOBuilder
            );
        }
    ),

    ReservationController::class => factory(
        static function (
            ListerReservationsService $listerReservationsService,
            TrouverReservationService $trouverReservationService,
            ListerSallesService $listerSallesService,
            CreerReservationService $creerReservationService,
            AnnulerReservationService $annulerReservationService,
            ReservationValidator $reservationValidator,
            CreerReservationDTOBuilder $reservationDTOBuilder
        ): ReservationController {
            return new ReservationController(
                $listerReservationsService,
                $trouverReservationService,
                $listerSallesService,
                $creerReservationService,
                $annulerReservationService,
                $reservationValidator,
                $reservationDTOBuilder
            );
        }
    ),

    Capsule::class => factory(
        function (): Capsule {
            return require dirname(__DIR__) . '/config/database.php';
        }
    ),

    Dispatcher::class => factory(
        function (): Dispatcher {
            return simpleDispatcher(
                require dirname(__DIR__) . '/routes/web.php'
            );
        }
    ),

    Application::class => factory(
        function (
            Dispatcher $dispatcher,
            ContainerInterface $container
        ): Application {
            return new Application(
                $dispatcher,
                fn (string $controllerClass) =>
                    $container->get($controllerClass)
            );
        }
    ),
];








//factory() sert à dire au conteneur PHP-DI comment fabriquer un objet.