<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('partenaire')) {
            Schema::create('partenaire', function (Blueprint $table) {
                $table->integer('id_partenaire')->autoIncrement();
                $table->string('nom', 150);
                $table->string('secteur', 100)->nullable();
                $table->text('description')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('partenaire');
    }
};
