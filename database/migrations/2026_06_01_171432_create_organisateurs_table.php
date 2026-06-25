<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisateurs', function (Blueprint $table) {
    $table->string('nom_structure');
    $table->string('type_structure');
    $table->text('description')->nullable();
    $table->foreignId('id_utilisateur')->constrained('users');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('organisateurs');
    }
};
