<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('groupe')) {
            Schema::create('groupe', function (Blueprint $table) {
                $table->integer('id_groupe')->autoIncrement();
                $table->string('nom', 100);
                $table->string('code_invitation', 50)->nullable();
                $table->integer('id_utilisateur_createur');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('groupe');
    }
};
