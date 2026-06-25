<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres_groupe', function (Blueprint $table) {
            $table->id();

            // Groupe lié
            $table->foreignId('id_groupe')
                ->constrained('groupes')
                ->onDelete('cascade');

            // Utilisateur lié
            $table->foreignId('id_utilisateur')
                ->constrained('users')
                ->onDelete('cascade');

            // Optionnel : rôle dans le groupe (admin / membre)
            $table->string('role')->default('membre');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membres_groupe');
    }
};