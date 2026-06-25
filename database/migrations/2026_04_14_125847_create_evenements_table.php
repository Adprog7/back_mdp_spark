<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->text('description');

            $table->dateTime('date_debut');
            $table->dateTime('date_fin');

            $table->string('lieu');
            $table->integer('capacite');

            $table->string('statut')->default('actif');

            $table->float('empreinte_carbone')->nullable();

            $table->foreignId('organisateur_id')
                ->constrained('organisateurs')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};