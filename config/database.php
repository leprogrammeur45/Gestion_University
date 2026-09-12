<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => $_ENV['DB_DRIVER'],
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;










//Dotenv recupere les informations .env
//prepare la connexion avec eloquent
//rend la configuration accessible globalement
//demarre eloquent