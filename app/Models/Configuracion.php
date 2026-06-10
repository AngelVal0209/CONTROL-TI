<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Configuracion extends Model
{
    protected $fillable = [
        'equipo_id',
        'sistema_operativo',
        'version',
        'office',
        'antivirus',
        'cpu',
        'ram',
        'disco',
        'fecha_actualizacion',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(ConfiguracionHistorial::class);
    }
}
