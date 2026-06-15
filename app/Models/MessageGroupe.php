<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageGroupe extends Model
{
    protected $table = 'message_groupe';
    protected $primaryKey = 'id_message_groupe';
    public $timestamps = false;

    protected $fillable = [
        'id_groupe',
        'id_utilisateur_expediteur',
        'contenu',
        'date_envoi',
    ];

    protected $casts = [
        'date_envoi' => 'datetime',
    ];

    // Relations
    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'id_groupe', 'id_groupe');
    }

    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur_expediteur', 'id_utilisateur');
    }
}
