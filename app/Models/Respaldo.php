<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Respaldo extends Model
{
    protected $fillable = [
        'equipo_id',
        'tipo',
        'ubicacion',
        'fecha_respaldo',
        'tamano',
        'responsable',
        'observaciones',
        'archivo',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }
}
