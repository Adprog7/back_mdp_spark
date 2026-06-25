<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            
            if (!Schema::hasColumn('billets', 'prix')) {
                $table->decimal('prix', 8, 2)->nullable();
            }

            if (!Schema::hasColumn('billets', 'date_achat')) {
                $table->timestamp('date_achat')->nullable();
            }

            if (!Schema::hasColumn('billets', 'statut')) {
                $table->string('statut')->default('en_attente');
            }

            if (!Schema::hasColumn('billets', 'id_utilisateur')) {
                // J'ai remplacé 'users' par 'utilisateurs' pour éviter un autre crash !
                $table->foreignId('id_utilisateur')
                      ->constrained('utilisateurs')
                      ->onDelete('cascade');
            }

            if (!Schema::hasColumn('billets', 'id_evenement')) {
                $table->foreignId('id_evenement')
                      ->constrained('evenements')
                      ->onDelete('cascade');
            }

        });
    }

    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            
            if (Schema::hasColumn('billets', 'id_utilisateur')) {
                $table->dropForeign(['id_utilisateur']);
                $table->dropColumn('id_utilisateur');
            }

            if (Schema::hasColumn('billets', 'id_evenement')) {
                $table->dropForeign(['id_evenement']);
                $table->dropColumn('id_evenement');
            }

            if (Schema::hasColumn('billets', 'prix')) {
                $table->dropColumn('prix');
            }

            if (Schema::hasColumn('billets', 'date_achat')) {
                $table->dropColumn('date_achat');
            }

            if (Schema::hasColumn('billets', 'statut')) {
                $table->dropColumn('statut');
            }

        });
    }
};