<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// BelongsTo représente une relation :
// Cette réservation appartient à une salle.
// C'est la relation inverse de Salle::hasMany().
class Reservation extends Model
{
    protected $table = 'reservations';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'salle_id',
        'responsable',
        'email',
        'motif',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }
}
