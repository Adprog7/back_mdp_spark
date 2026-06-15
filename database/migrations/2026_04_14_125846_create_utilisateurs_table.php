<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('utilisateur')) {
            Schema::create('utilisateur', function (Blueprint $table) {
                $table->integer('id_utilisateur')->autoIncrement();
                $table->string('nom', 100);
                $table->string('prenom', 100);
                $table->string('email', 150);
                $table->string('mot_de_passe', 255);
                $table->dateTime('date_inscription')->nullable();
                $table->string('langue', 10)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
