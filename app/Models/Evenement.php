<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $table = 'evenements';

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'prix',
        'capacite',
        'statut',
        'empreinte_carbone',
        'organisateur_id',
        'photo',
    ];

    // Relations
    public function organisateur()
    {
        // On lie organisateur_id (table evenements) vers id (table organisateurs)
        return $this->belongsTo(\App\Models\Organisateur::class, 'organisateur_id', 'id');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class, 'id_evenement', 'id');
    }

    public function sponsorisations()
    {
        return $this->hasMany(Sponsorisation::class, 'id_evenement', 'id');
    }

    public function notations()
    {
        return $this->hasMany(Notation::class, 'id_evenement', 'id');
    }
}
