<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {

            $table->decimal('montant', 8, 2);
            $table->timestamp('date_paiement')->nullable();
            $table->string('moyen_paiement'); // carte, paypal, cash...
            $table->string('statut')->default('en_attente');

            $table->foreignId('id_billet')
                ->constrained('billets')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {

            $table->dropForeign(['id_billet']);

            $table->dropColumn([
                'montant',
                'date_paiement',
                'moyen_paiement',
                'statut',
                'id_billet'
            ]);

        });
    }
};
