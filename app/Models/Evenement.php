<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    protected $table = 'evenements';
    protected $primaryKey = 'id';
    public $timestamps = true;

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
        return $this->belongsTo(Organisateur::class, 'organisateur_id', 'id_organisateur');
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
