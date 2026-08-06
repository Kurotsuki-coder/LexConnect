<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $table = 'dossiers';
    protected $primaryKey = 'id_dossier';

    protected $fillable = [
        'id_citoyen', 'motif', 'description', 'budget',
        'statut_dossier', 'niveau_urgence',
    ];

    public function citoyen()
    {
        return $this->belongsTo(Citoyen::class, 'id_citoyen', 'id_citoyen');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_dossier', 'id_dossier');
    }
}