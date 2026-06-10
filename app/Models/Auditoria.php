<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auditoria extends Model
{
    protected $table = 'auditoria';

    protected $fillable = [
        'usuario_id',
        'accion',
        'modulo',
        'registro_id',
        'detalle',
        'ip',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
