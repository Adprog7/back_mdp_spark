<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('evenement')) {
            Schema::create('evenement', function (Blueprint $table) {
                $table->integer('id_evenement')->autoIncrement();
                $table->string('titre', 200);
                $table->text('description')->nullable();
                $table->dateTime('date_debut');
                $table->dateTime('date_fin');
                $table->string('lieu', 255)->nullable();
                $table->integer('capacite')->nullable();
                $table->string('statut', 50)->nullable();
                $table->decimal('empreinte_carbonne', 10, 2)->nullable();
                $table->integer('id_organisateur');
                $table->string('affiche', 255)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('evenement');
    }
};
