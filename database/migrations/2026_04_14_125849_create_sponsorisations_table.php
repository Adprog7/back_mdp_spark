<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sponsorisation')) {
            Schema::create('sponsorisation', function (Blueprint $table) {
                $table->integer('id_sponsorisation')->autoIncrement();
                $table->decimal('montant', 12, 2)->nullable();
                $table->string('type_visibilite', 100)->nullable();
                $table->integer('id_evenement');
                $table->integer('id_partenaire');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorisation');
    }
};
