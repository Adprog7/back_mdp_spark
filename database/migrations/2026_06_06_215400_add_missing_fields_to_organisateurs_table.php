<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisateurs', function (Blueprint $table) {
            if (!Schema::hasColumn('organisateurs', 'nom_structure')) {
                $table->string('nom_structure')->after('id');
            }

            if (!Schema::hasColumn('organisateurs', 'type_structure')) {
                $table->string('type_structure')->default('organisation')->after('nom_structure');
            }

            if (!Schema::hasColumn('organisateurs', 'description')) {
                $table->text('description')->nullable()->after('type_structure');
            }

            if (!Schema::hasColumn('organisateurs', 'id_utilisateur')) {
                $table->foreignId('id_utilisateur')->after('description')->constrained('users');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organisateurs', function (Blueprint $table) {
            if (Schema::hasColumn('organisateurs', 'id_utilisateur')) {
                $table->dropConstrainedForeignId('id_utilisateur');
            }

            if (Schema::hasColumn('organisateurs', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('organisateurs', 'type_structure')) {
                $table->dropColumn('type_structure');
            }

            if (Schema::hasColumn('organisateurs', 'nom_structure')) {
                $table->dropColumn('nom_structure');
            }
        });
    }
};
