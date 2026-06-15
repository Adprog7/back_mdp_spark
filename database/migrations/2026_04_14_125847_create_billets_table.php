<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('billet')) {
            Schema::create('billet', function (Blueprint $table) {
                $table->integer('id_billet')->autoIncrement();
                $table->decimal('prix', 10, 2);
                $table->dateTime('date_achat')->nullable();
                $table->string('statut', 50)->nullable();
                $table->integer('id_utilisateur');
                $table->integer('id_evenement');
                $table->integer('id_groupe')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('billet');
    }
};
