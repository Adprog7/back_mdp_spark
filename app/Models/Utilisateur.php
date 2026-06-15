<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';
    public $timestamps = false;

    use HasApiTokens, Notifiable;

    /**
     * Obtenir le mot de passe pour l'authentification.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    protected $hidden = [
        'mot_de_passe',
    ];

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

    public function groupesRejoints()
    {
        return $this->belongsToMany(Groupe::class, 'membre_groupe', 'id_utilisateur', 'id_groupe')
                    ->withPivot('id_membre_groupe', 'date_rejoint');
    }

    public function messagesGroupes()
    {
        return $this->hasMany(MessageGroupe::class, 'id_utilisateur_expediteur', 'id_utilisateur');
    }

    public function paiementsEffectues()
    {
        return $this->hasMany(Paiement::class, 'id_utilisateur_payeur', 'id_utilisateur');
    }

    public function organisateur()
    {
        return $this->hasOne(Organisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
}
