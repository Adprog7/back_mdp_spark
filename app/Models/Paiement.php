<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiement';
    protected $primaryKey = 'id_paiement';
    public $timestamps = false;

    protected $fillable = [
        'montant',
        'moyen_paiement',
        'statut',
        'id_billet',
    ];

    // Relations
    public function billet()
    {
        return $this->belongsTo(Billet::class, 'id_billet', 'id_billet');
    }
}
