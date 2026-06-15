<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On s'assure qu'il y a au moins 2 utilisateurs pour créer une amitié
        $user1Id = DB::table('utilisateurs')->insertGetId([
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user2Id = DB::table('utilisateurs')->insertGetId([
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Création de la relation d'amitié (ex: Moi et Killian)
        DB::table('amis')->insert([
            'utilisateur_id' => $user1Id,
            'ami_id' => $user2Id,
            'status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('amis')->insert([
            'utilisateur_id' => $user2Id,
            'ami_id' => $user1Id,
            'status' => 'accepted',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
