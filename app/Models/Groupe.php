<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $table = 'groupe'; // Ton dump SQL indique 'groupe'
    protected $primaryKey = 'id_groupe';
    public $timestamps = false;

    protected $fillable = ['nom', 'code_invitation', 'id_utilisateur_createur'];

    // Ajoute cette relation pour récupérer les membres
    // Dans App\Models\Groupe.php
    public function membres()
    {
        // On lie le groupe aux utilisateurs via la table membres_groupe
        return $this->belongsToMany(
            User::class,            // Le modèle cible
            'membres_groupe',       // La table pivot
            'id_groupe',            // Clé étrangère dans la pivot pointant vers Groupe
            'id_utilisateur'        // Clé étrangère dans la pivot pointant vers User
        );
    }
}