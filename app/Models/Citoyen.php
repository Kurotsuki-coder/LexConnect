<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citoyen extends Model
{
    protected $table = 'citoyens';
    protected $primaryKey = 'id_citoyen';
    public $timestamps = false;

    protected $fillable = ['id_utilisateur'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_citoyen', 'id_citoyen');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_citoyen', 'id_citoyen');
    }
}