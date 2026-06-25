<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable  
{
    use HasApiTokens;

    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'langue',
    ];

    // Relations
    public function billets()
    {
        return $this->hasMany(Billet::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function groupes()
    {
        return $this->hasMany(Groupe::class, 'id_utilisateur_createur', 'id_utilisateur');
    }

    public function organisateur()
    {
        return $this->hasOne(Organisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
}
