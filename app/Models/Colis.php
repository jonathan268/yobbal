<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colis extends Model
{
    protected $fillable = [
        'user_id',
        'expediteur',
        'destinataire',
        'ville_depart',
        'ville_arrivee',
        'poids',
        'statut'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
