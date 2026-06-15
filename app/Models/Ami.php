<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ami extends Model
{
    protected $table = 'amis';

    protected $fillable = [
        'id_demandeur',
        'id_destinataire',
        'statut',
    ];

    // ─── Relations ────────────────────────────────────────────────

    /**
     * L'utilisateur qui a envoyé la demande d'amitié.
     */
    public function demandeur()
    {
        return $this->belongsTo(User::class, 'id_demandeur');
    }

    /**
     * L'utilisateur qui a reçu la demande d'amitié.
     */
    public function destinataire()
    {
        return $this->belongsTo(User::class, 'id_destinataire');
    }
}
