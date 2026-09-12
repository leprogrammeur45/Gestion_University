<?php

declare(strict_types=1);//typage strict

use App\Factory\ContainerFactory;
use App\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = ContainerFactory::create(dirname(__DIR__));

$application = $container->get(Application::class);

$application->run();