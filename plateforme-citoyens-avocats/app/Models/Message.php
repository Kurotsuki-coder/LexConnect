<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table='messages';
    protected $primaryKey='id_message';
    public $timestamps=false;

    protected $fillable=[
        'id_dossier',
        'id_expediteur',
        'id_receveur',
        'contenu',
        'heure',
        'statut_message',
        'chemin_fichier',
        'nom_fichier',
        'type_fichier',
        'taille_fichier'
    ];

    public function dossier():BelongsTo
    {
        return $this->belongsTo(
            Dossier::class,
            'id_dossier',
            'id_dossier'
        );
    }

    public function expediteur():BelongsTo
    {
        return $this->belongsTo(
            Utilisateur::class,
            'id_expediteur',
            'id_utilisateur'
        );
    }

    public function receveur():BelongsTo
    {
        return $this->belongsTo(
            Utilisateur::class,
            'id_receveur',
            'id_utilisateur'
        );
    }
}