<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        $capsule = require __DIR__ . '/../../config/database.php';

        $capsule->schema()->create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salle_id')
                ->constrained('salles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('responsable', 150);
            $table->string('email', 255);
            $table->string('motif', 255);

            $table->dateTime('date_debut');
            $table->dateTime('date_fin');

            $table->enum('statut', [
                'confirmee',
                'annulee',
            ])->default('confirmee');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        $capsule = require __DIR__ . '/../../config/database.php';

        $capsule->schema()->dropIfExists('reservations');
    }
};