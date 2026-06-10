<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantenimiento extends Model
{
    protected $fillable = [
        'equipo_id',
        'tipo',
        'fecha_programada',
        'fecha_realizada',
        'tecnico',
        'descripcion',
        'costo',
        'estado',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }
}
