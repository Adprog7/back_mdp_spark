<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisateur extends Model
{
    protected $table = 'organisateur';
    protected $primaryKey = 'id_organisateur';
    public $timestamps = false;

    protected $fillable = [
        'nom_structure',
        'type_structure',
        'description',
        'id_utilisateur',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_organisateur', 'id_organisateur');
    }
}
