<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void//cree  modifier
    {
        Schema::create('reservations', function (Blueprint $table) {
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

    public function down(): void//annule supprime
    {
        Schema::dropIfExists('reservations');
    }
};





//Une migration sert à décrire la structure de la base de données dans du code.
