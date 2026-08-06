<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';
    protected $primaryKey = 'id_avis';
    public $timestamps = false;

    protected $fillable = [
        'id_citoyen', 'id_avocat', 'note', 'commentaire', 'date',
    ];

    public function citoyen()
    {
        return $this->belongsTo(Citoyen::class, 'id_citoyen', 'id_citoyen');
    }

    public function avocat()
    {
        return $this->belongsTo(Avocat::class, 'id_avocat', 'id_avocat');
    }
}