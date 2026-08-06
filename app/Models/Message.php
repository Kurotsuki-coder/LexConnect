<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id_message';
    public $timestamps = false;

    protected $fillable = [
        'contenu', 'statut_message', 'heure', 'id_expediteur', 'id_receveur',
    ];

    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_expediteur', 'id_utilisateur');
    }

    public function receveur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_receveur', 'id_utilisateur');
    }
}