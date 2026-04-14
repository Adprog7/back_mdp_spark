<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $table = 'evenement';
    protected $primaryKey = 'id_evenement';
    public $timestamps = false;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'capacite',
        'statut',
        'empreinte_carbonne',
        'id_organisateur',
    ];

    // Relations
    public function organisateur()
    {
        return $this->belongsTo(Organisateur::class, 'id_organisateur', 'id_organisateur');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class, 'id_evenement', 'id_evenement');
    }

    public function sponsorisations()
    {
        return $this->hasMany(Sponsorisation::class, 'id_evenement', 'id_evenement');
    }

    public function notations()
    {
        return $this->hasMany(Notation::class, 'id_evenement', 'id_evenement');
    }
}
