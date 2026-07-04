<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billets', function (Blueprint $table) {
            $table->id('id_billet');
            $table->decimal('prix', 8, 2);
            $table->dateTime('date_achat');
            $table->string('statut');
            $table->unsignedBigInteger('id_utilisateur');
            $table->unsignedBigInteger('id_evenement');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billets');
    }
};
