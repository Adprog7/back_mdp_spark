<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisateur extends Model
{
    protected $table = 'organisateurs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nom_structure',
        'type_structure',
        'description',
        'id_utilisateur',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'organisateur_id');
    }
}
