<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    protected $table = 'salles';

    protected $fillable = [
        'nom',
        'batiment',
        'capacite',
        'type',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'salle_id');
    }
}
