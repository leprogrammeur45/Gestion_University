<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

$basePath = dirname(__DIR__);

$dotenv = Dotenv::createImmutable($basePath);

if (is_file($basePath . '/.env')) {
    $dotenv->load();
}

$capsule = new Capsule();

$options = [];

if (!empty($_ENV['DB_SSL_CA']) && is_file($_ENV['DB_SSL_CA'])) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $_ENV['DB_SSL_CA'];
}

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
    'options' => $options,
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;