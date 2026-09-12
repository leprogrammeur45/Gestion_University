<?php

namespace App\Factory;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    public static function create(string $projectDirectory): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($projectDirectory . '/config/container.php');

        return $builder->build();
    }
}

//Une Factory est une classe dont le rôle est de créer quelque chose.