<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renovacion extends Model
{
    protected $table = 'renovaciones';

    protected $fillable = [
        'nombre',
        'proveedor',
        'tipo',
        'monto',
        'moneda',
        'fecha_inicio',
        'fecha_vencimiento',
        'periodo',
        'estado',
        'archivo',
        'descripcion',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_vencimiento' => 'date',
            'monto' => 'decimal:2',
        ];
    }
}
