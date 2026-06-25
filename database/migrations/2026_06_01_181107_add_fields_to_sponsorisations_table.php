<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sponsorisations', function (Blueprint $table) {
            $table->decimal('montant', 8, 2);
            $table->string('type_visibilite');

            $table->foreignId('id_evenement')
                ->constrained('evenements')
                ->onDelete('cascade');

            $table->foreignId('id_partenaire')
                ->constrained('partenaires')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sponsorisations', function (Blueprint $table) {
            $table->dropForeign(['id_evenement']);
            $table->dropForeign(['id_partenaire']);

            $table->dropColumn([
                'montant',
                'type_visibilite',
                'id_evenement',
                'id_partenaire'
            ]);
        });
    }
};