<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salles', function (Blueprint $table) {
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
        Schema::dropIfExists('salles');
    }
};






//Une migration sert à décrire la structure de la base de données dans du code.