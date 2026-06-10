<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracionHistorial extends Model
{
    protected $table = 'configuraciones_historial';

    protected $fillable = [
        'configuracion_id',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'usuario_id',
    ];

    public function configuracion(): BelongsTo
    {
        return $this->belongsTo(Configuracion::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
