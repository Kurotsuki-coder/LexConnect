<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dossier extends Model
{
    protected $table='dossiers';
    protected $primaryKey='id_dossier';
    public $timestamps=false;

    protected $fillable=[
        'id_citoyen',
        'motif',
        'description',
        'budget',
        'statut_dossier',
        'niveau_urgence'
    ];

    public function citoyen():BelongsTo
    {
        return $this->belongsTo(
            Citoyen::class,
            'id_citoyen',
            'id_citoyen'
        );
    }

    public function demandes():HasMany
    {
        return $this->hasMany(
            Demande::class,
            'id_dossier',
            'id_dossier'
        );
    }

    public function messages():HasMany
    {
        return $this->hasMany(
            Message::class,
            'id_dossier',
            'id_dossier'
        );
    }

    public function getRouteKeyName():string
    {
        return 'id_dossier';
    }
}