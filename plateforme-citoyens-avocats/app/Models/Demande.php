<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $table='demandes';
    protected $primaryKey='id_demande';
    public $timestamps=false;

    protected $fillable=[
        'id_dossier',
        'id_avocat',
        'message',
        'statut_demande',
        'heure',
        'origine'
    ];

    public function getRouteKeyName()
    {
        return 'id_demande';
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class,'id_dossier','id_dossier');
    }

    public function avocat()
    {
        return $this->belongsTo(Avocat::class,'id_avocat','id_avocat');
    }
}