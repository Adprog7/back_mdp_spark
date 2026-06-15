<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notation')) {
            Schema::create('notation', function (Blueprint $table) {
                $table->integer('id_notation')->autoIncrement();
                $table->string('categorie', 100)->nullable();
                $table->integer('niveau_sonore')->nullable();
                $table->integer('qualite_air')->nullable();
                $table->integer('confort')->nullable();
                $table->integer('id_evenement');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notation');
    }
};
