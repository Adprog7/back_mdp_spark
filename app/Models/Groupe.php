<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $table = 'groupe';
    protected $primaryKey = 'id_groupe';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'code_invitation',
        'id_utilisateur_createur',
    ];

    // Relations
    public function creator()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur_createur', 'id_utilisateur');
    }

    public function membres()
    {
        return $this->belongsToMany(Utilisateur::class, 'membre_groupe', 'id_groupe', 'id_utilisateur')
                    ->withPivot('id_membre_groupe', 'date_rejoint');
    }

    public function messages()
    {
        return $this->hasMany(MessageGroupe::class, 'id_groupe', 'id_groupe');
    }

    public function billets()
    {
        return $this->hasMany(Billet::class, 'id_groupe', 'id_groupe');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_groupe', 'id_groupe');
    }
}
