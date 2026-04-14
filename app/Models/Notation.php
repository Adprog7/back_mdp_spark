<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notation extends Model
{
    protected $table = 'notation';
    protected $primaryKey = 'id_notation';
    public $timestamps = false;

    protected $fillable = [
        'categorie',
        'niveau_sonore',
        'qualite_air',
        'confort',
        'id_evenement',
    ];

    // Relations
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_evenement', 'id_evenement');
    }
}
