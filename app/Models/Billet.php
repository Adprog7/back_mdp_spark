<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    protected $table = 'billet';
    protected $primaryKey = 'id_billet';
    public $timestamps = false;

    protected $fillable = [
        'prix',
        'statut',
        'id_utilisateur',
        'id_evenement',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_evenement', 'id_evenement');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'id_billet', 'id_billet');
    }
}
