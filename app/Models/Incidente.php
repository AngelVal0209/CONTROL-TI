<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incidente extends Model
{
    protected $fillable = [
        'equipo_id',
        'usuario_reporta_id',
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_reporte',
        'fecha_resolucion',
        'evidencias',
        'solucion',
    ];

    protected function casts(): array
    {
        return [
            'evidencias' => 'array',
            'fecha_reporte' => 'datetime',
            'fecha_resolucion' => 'datetime',
        ];
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function usuarioReporta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_reporta_id');
    }
}
