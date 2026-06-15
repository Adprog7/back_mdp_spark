<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('paiement')) {
            Schema::create('paiement', function (Blueprint $table) {
                $table->integer('id_paiement')->autoIncrement();
                $table->decimal('montant', 10, 2);
                $table->dateTime('date_paiement')->nullable();
                $table->string('moyen_paiement', 50)->nullable();
                $table->string('statut', 50)->nullable();
                $table->integer('id_billet');
                $table->integer('id_utilisateur_payeur')->nullable();
                $table->integer('id_groupe')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement');
    }
};
