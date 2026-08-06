<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilAvocat extends Model
{
    protected $table = 'profil_avocats';
    protected $primaryKey = 'id_profil';

    protected $fillable = [
        'id_avocat', 'specialites', 'bio',
        'disponibilite', 'horaire', 'numero_barre',
    ];

    public function avocat()
    {
        return $this->belongsTo(Avocat::class, 'id_avocat', 'id_avocat');
    }
}