<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = require_once __DIR__ . '/../config/database.php';

$migrations = [
    __DIR__ . '/migrations/001_create_salles.php',
    __DIR__ . '/migrations/002_create_reservation.php',
];

foreach ($migrations as $migrationFile) {
    $migration = require $migrationFile;

    $migration->up();

    echo "Migration exécutée : " . basename($migrationFile) . PHP_EOL;
}

echo "Toutes les migrations ont été exécutées." . PHP_EOL;

