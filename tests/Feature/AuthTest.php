<?php

namespace Tests\Feature;

use App\Models\Utilisateur;
use App\Models\Organisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'nom' => 'Macaire',
            'prenom' => 'Adrien',
            'email' => 'adrien.test@sparkup.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'user' => ['id', 'nom', 'prenom', 'email']
                 ]);

        $this->assertDatabaseHas('utilisateur', [
            'email' => 'adrien.test@sparkup.com',
            'nom' => 'Macaire',
            'prenom' => 'Adrien',
        ]);
    }

    /** @test */
    public function test_an_organizer_can_register()
    {
        $response = $this->postJson('/api/register', [
            'nom' => 'Organisateur',
            'prenom' => 'Pro',
            'email' => 'organisateur@sparkup.com',
            'password' => 'password123',
            'nom_structure' => 'SparkUp Agency',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'user' => [
                         'id', 'nom', 'prenom', 'email',
                         'organisateur' => ['id_organisateur', 'nom_structure']
                     ]
                 ]);

        $this->assertDatabaseHas('utilisateur', [
            'email' => 'organisateur@sparkup.com',
        ]);

        $this->assertDatabaseHas('organisateur', [
            'nom_structure' => 'SparkUp Agency',
        ]);
    }

    /** @test */
    public function test_a_user_can_login()
    {
        $user = Utilisateur::create([
            'nom' => 'Macaire',
            'prenom' => 'Adrien',
            'email' => 'adrien.test@sparkup.com',
            'mot_de_passe' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'adrien.test@sparkup.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'user' => ['id', 'nom', 'prenom', 'email']
                 ]);
    }

    /** @test */
    public function test_a_user_cannot_login_with_invalid_credentials()
    {
        $user = Utilisateur::create([
            'nom' => 'Macaire',
            'prenom' => 'Adrien',
            'email' => 'adrien.test@sparkup.com',
            'mot_de_passe' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'adrien.test@sparkup.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
