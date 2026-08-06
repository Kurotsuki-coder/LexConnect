<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom', 'prenom', 'email', 'mot_de_passe',
        'telephone', 'role', 'statut', 'region',
    ];

    protected $hidden = ['mot_de_passe'];

    // Indique à Laravel où trouver le mot de passe (colonne mot_de_passe au lieu de password)
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function citoyen()
    {
        return $this->hasOne(Citoyen::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function avocat()
    {
        return $this->hasOne(Avocat::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'id_expediteur', 'id_utilisateur');
    }

    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'id_receveur', 'id_utilisateur');
    }

    public function getRouteKeyName()
    {
        return 'id_utilisateur';
    }
}