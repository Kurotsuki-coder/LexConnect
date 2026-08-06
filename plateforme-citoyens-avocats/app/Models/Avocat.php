<?php
// app/Models/Avocat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avocat extends Model
{
    protected $table = 'avocats';
    protected $primaryKey = 'id_avocat';
    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur', 'score_bayesien', 'nb_avis', 'note_moyenne_ponderee', 'score_calcule_at',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function profil()
    {
        return $this->hasOne(ProfilAvocat::class, 'id_avocat', 'id_avocat');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_avocat', 'id_avocat');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_avocat', 'id_avocat');
    }

    public function dossiers()
    {
        return $this->hasManyThrough(
            Dossier::class,
            Demande::class,
            'id_avocat',
            'id_dossier',
            'id_avocat',
            'id_dossier'
        );
    }

    public function scopeClasses($query)
    {
        return $query->orderByDesc('score_bayesien');
    }
}