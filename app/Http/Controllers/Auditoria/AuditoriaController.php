<?php

namespace App\Http\Controllers\Auditoria;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Auditoria::with('usuario');

        if ($modulo = $request->get('modulo')) {
            $query->where('modulo', $modulo);
        }

        if ($usuarioId = $request->get('usuario_id')) {
            $query->where('usuario_id', $usuarioId);
        }

        if ($fechaDesde = $request->get('fecha_desde')) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }

        if ($fechaHasta = $request->get('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        return Inertia::render('Auditoria/Index', [
            'auditorias' => $query->latest()->paginate(10)->withQueryString(),
            'usuarios' => User::all(),
            'modulos' => collect(['equipos', 'incidentes', 'configuraciones', 'mantenimientos', 'respaldos', 'usuarios']),
        ]);
    }
}


