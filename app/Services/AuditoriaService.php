<?php

namespace App\Services;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Request;

class AuditoriaService
{
    public static function registrar(string $accion, string $modulo, ?int $registroId = null, ?string $detalle = null): void
    {
        Auditoria::create([
            'usuario_id' => auth()->id(),
            'accion' => $accion,
            'modulo' => $modulo,
            'registro_id' => $registroId,
            'detalle' => $detalle,
            'ip' => request()->ip(),
        ]);
    }
}
