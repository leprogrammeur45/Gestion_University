<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;

// Charger la connexion Eloquent
require_once __DIR__ . '/../config/database.php';

$salles = [
    [
        'nom' => 'Amphithéâtre A',
        'batiment' => 'Bâtiment A',
        'capacite' => 250,
        'type' => 'amphitheatre',
        'active' => true,
    ],
    [
        'nom' => 'Salle B12',
        'batiment' => 'Bâtiment B',
        'capacite' => 40,
        'type' => 'cours',
        'active' => true,
    ],
    [
        'nom' => 'Laboratoire Chimie',
        'batiment' => 'Bâtiment C',
        'capacite' => 24,
        'type' => 'laboratoire',
        'active' => true,
    ],
    [
        'nom' => 'Salle Informatique 1',
        'batiment' => 'Bâtiment D',
        'capacite' => 30,
        'type' => 'informatique',
        'active' => true,
    ],
    [
        'nom' => 'Salle de réunion',
        'batiment' => 'Bâtiment E',
        'capacite' => 12,
        'type' => 'reunion',
        'active' => true,
    ],
];

foreach ($salles as $donnees) {
    $salle = Salle::firstOrCreate(
        [
            'nom' => $donnees['nom'],
            'batiment' => $donnees['batiment'],
        ],
        $donnees
    );

    if ($salle->wasRecentlyCreated) {
        echo "Salle créée : {$salle->nom}" . PHP_EOL;
    } else {
        echo "Salle déjà existante : {$salle->nom}" . PHP_EOL;
    }
}

echo PHP_EOL;
echo "Seeder terminé." . PHP_EOL;