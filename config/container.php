<?php

use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use DI\ContainerBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();

$builder->addDefinitions([
    SalleRepositoryInterface::class => function () {
        return new SalleRepository();
    },

    ReservationRepositoryInterface::class => function () {
        return new ReservationRepository();
    },

    SalleController::class => function ($container) {
        return new SalleController(
            $container->get(SalleRepositoryInterface::class),
            $container->get(\App\Service\CreerSalleService::class),
            $container->get(\App\Validation\SalleValidator::class)
        );
    },

    ReservationController::class => function ($container) {
        return new ReservationController(
            $container->get(ReservationRepositoryInterface::class),
            $container->get(SalleRepositoryInterface::class),
            $container->get(\App\Service\CreerReservationService::class),
            $container->get(\App\Service\AnnulerReservationService::class),
            $container->get(\App\Validation\ReservationValidator::class)
        );
    },
]);

return $builder->build();
