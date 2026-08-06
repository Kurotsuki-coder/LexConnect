<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avocat extends Model
{
    protected $table = 'avocats';
    protected $primaryKey = 'id_avocat';
    public $timestamps = false;

    protected $fillable = ['id_utilisateur'];

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
}