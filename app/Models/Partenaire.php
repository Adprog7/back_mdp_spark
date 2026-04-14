<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $table = 'partenaire';
    protected $primaryKey = 'id_partenaire';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'secteur',
        'description',
    ];

    // Relations
    public function sponsorisations()
    {
        return $this->hasMany(Sponsorisation::class, 'id_partenaire', 'id_partenaire');
    }
}
