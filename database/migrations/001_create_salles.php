<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        $capsule = require __DIR__ . '/../../config/database.php';

        $capsule->schema()->create('salles', function (Blueprint $table) {
            $table->id();

            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->integer('capacite');

            $table->enum('type', [
                'cours',
                'informatique',
                'laboratoire',
                'amphitheatre',
                'reunion',
            ]);

            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        $capsule = require __DIR__ . '/../../config/database.php';

        $capsule->schema()->dropIfExists('salles');
    }
};