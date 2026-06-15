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
        if (!Schema::hasTable('message_groupe')) {
            Schema::create('message_groupe', function (Blueprint $table) {
                $table->integer('id_message_groupe')->autoIncrement();
                $table->integer('id_groupe');
                $table->integer('id_utilisateur_expediteur');
                $table->text('contenu');
                $table->timestamp('date_envoi')->useCurrent();

                $table->foreign('id_groupe')->references('id_groupe')->on('groupe')->onDelete('cascade');
                $table->foreign('id_utilisateur_expediteur')->references('id_utilisateur')->on('utilisateur')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_groupe');
    }
};
