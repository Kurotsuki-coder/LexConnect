<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'id_contact';

    protected $fillable = ['nom', 'email', 'sujet', 'message', 'statut', 'id_utilisateur'];

    public function getRouteKeyName()
    {
        return 'id_contact';
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
}