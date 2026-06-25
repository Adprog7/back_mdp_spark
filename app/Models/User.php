<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // N'oublie pas d'importer ceci pour createToken

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur'; // Ta table utilise id_utilisateur
    public $timestamps = false;

    protected $fillable = [
        'nom', 'prenom', 'email', 'mot_de_passe', 'langue', 'date_inscription'
    ];

    // Indique à Laravel quel champ utiliser pour le mot de passe
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}