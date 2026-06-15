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
        Schema::table('billet', function (Blueprint $table) {
            if (!Schema::hasColumn('billet', 'id_groupe')) {
                $table->integer('id_groupe')->nullable();
                $table->foreign('id_groupe')->references('id_groupe')->on('groupe')->onDelete('set null');
            }
        });

        Schema::table('paiement', function (Blueprint $table) {
            if (!Schema::hasColumn('paiement', 'id_utilisateur_payeur')) {
                $table->integer('id_utilisateur_payeur')->nullable();
                $table->foreign('id_utilisateur_payeur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
            }
            if (!Schema::hasColumn('paiement', 'id_groupe')) {
                $table->integer('id_groupe')->nullable();
                $table->foreign('id_groupe')->references('id_groupe')->on('groupe')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billet', function (Blueprint $table) {
            if (Schema::hasColumn('billet', 'id_groupe')) {
                $table->dropForeign(['id_groupe']);
                $table->dropColumn('id_groupe');
            }
        });

        Schema::table('paiement', function (Blueprint $table) {
            if (Schema::hasColumn('paiement', 'id_utilisateur_payeur')) {
                $table->dropForeign(['id_utilisateur_payeur']);
                $table->dropColumn('id_utilisateur_payeur');
            }
            if (Schema::hasColumn('paiement', 'id_groupe')) {
                $table->dropForeign(['id_groupe']);
                $table->dropColumn('id_groupe');
            }
        });
    }
};
