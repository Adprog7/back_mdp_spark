<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table "amis" qui stocke les relations d'amitié entre utilisateurs.
     * statut : 'en_attente' | 'accepte' | 'refuse'
     */
    public function up(): void
    {
        Schema::create('amis', function (Blueprint $table) {
            $table->id();

            // L'utilisateur qui envoie la demande
            $table->foreignId('id_demandeur')
                  ->constrained('users')
                  ->onDelete('cascade');

            // L'utilisateur qui reçoit la demande
            $table->foreignId('id_destinataire')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->enum('statut', ['en_attente', 'accepte', 'refuse'])->default('en_attente');

            $table->timestamps();

            // Une seule demande possible entre deux utilisateurs
            $table->unique(['id_demandeur', 'id_destinataire']);
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('amis');
    }
};
