<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            // On ajoute le prix (max 999999.99)
            if (!Schema::hasColumn('evenements', 'prix')) {
                $table->decimal('prix', 8, 2)->default(0)->after('lieu');
            }
            
            // On ajoute la photo (pour correspondre à ton Modèle)
            if (!Schema::hasColumn('evenements', 'photo')) {
                $table->string('photo')->nullable()->after('prix');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            if (Schema::hasColumn('evenements', 'prix')) {
                $table->dropColumn('prix');
            }
            if (Schema::hasColumn('evenements', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
};