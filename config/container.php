<?php

use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use function DI\autowire;
use function DI\factory;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use PapaMamadouDiouf\GestionUniversity\Application;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

return [

    SalleRepositoryInterface::class => autowire(
        SalleRepository::class
    ),

    ReservationRepositoryInterface::class => autowire(
        ReservationRepository::class
    ),

    SalleValidator::class => autowire(),

    ReservationValidator::class => autowire(),

    CreerReservationService::class => autowire(),

    AnnulerReservationService::class => autowire(),

    SalleController::class => autowire(),

    ReservationController::class => autowire(),

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
                fn (string $controllerClass) => $container->get($controllerClass)
            );
        }
    ),

];