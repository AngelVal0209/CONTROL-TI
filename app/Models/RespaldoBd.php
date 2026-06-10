<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespaldoBd extends Model
{
    protected $table = 'respaldos_bd';

    protected $fillable = [
        'nombre',
        'descripcion',
        'archivo',
        'tamano',
        'fecha_respaldo',
        'responsable',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_respaldo' => 'datetime',
        ];
    }
}
