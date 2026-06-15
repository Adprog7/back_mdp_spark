<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notification')) {
            Schema::create('notification', function (Blueprint $table) {
                $table->integer('id_notification')->autoIncrement();
                $table->string('type', 50)->nullable();
                $table->text('contenu')->nullable();
                $table->dateTime('date_envoie')->nullable();
                $table->integer('id_utilisateur');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
