<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organisateur')) {
            Schema::create('organisateur', function (Blueprint $table) {
                $table->integer('id_organisateur')->autoIncrement();
                $table->string('nom_structure', 150);
                $table->string('type_structure', 100)->nullable();
                $table->text('description')->nullable();
                $table->integer('id_utilisateur')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organisateur');
    }
};
