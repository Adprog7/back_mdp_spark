<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsorisation extends Model
{
    protected $table = 'sponsorisation';
    protected $primaryKey = 'id_sponsorisation';
    public $timestamps = false;

    protected $fillable = [
        'montant',
        'type_visibilite',
        'id_evenement',
        'id_partenaire',
    ];

    // Relations
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'id_evenement', 'id_evenement');
    }

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class, 'id_partenaire', 'id_partenaire');
    }
}
