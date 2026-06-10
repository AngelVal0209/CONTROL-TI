<?php

namespace App\Services;

use App\Models\Equipo;
use App\Repositories\RespaldoRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RespaldoService extends BaseService
{
    protected function repositoryClass(): string
    {
        return RespaldoRepository::class;
    }

    public function indexData(Request $request)
    {
        $query = $this->repository->queryWithEquipo();

        if ($search = $request->get('global')) {
            $query->where(function ($q) use ($search) {
                $q->where('ubicacion', 'like', "%{$search}%")
                  ->orWhere('responsable', 'like', "%{$search}%");
            });
        }

        if ($equipoId = $request->get('equipo_id')) {
            $query->where('equipo_id', $equipoId);
        }

        if ($tipo = $request->get('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($ubicacion = $request->get('ubicacion')) {
            $query->where('ubicacion', $ubicacion);
        }

        return Inertia::render('Respaldos/Index', [
            'respaldos' => $query->latest()->paginate(10)->withQueryString(),
            'equipos' => Equipo::all(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('Respaldos/Form', [
            'equipos' => Equipo::all(),
        ]);
    }

    public function editData($respaldo)
    {
        $respaldo->load('equipo');
        return Inertia::render('Respaldos/Form', [
            'respaldo' => $respaldo,
            'equipos' => Equipo::all(),
        ]);
    }

    public function showData($respaldo)
    {
        $respaldo->load('equipo');
        return Inertia::render('Respaldos/Show', [
            'respaldo' => $respaldo,
        ]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'equipo_id' => 'nullable|exists:equipos,id',
            'tipo' => 'required|string',
            'ubicacion' => 'nullable|string',
            'fecha_respaldo' => 'required|date',
            'tamano' => 'nullable|string',
            'responsable' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        return parent::create($validated);
    }

    public function validateAndUpdate(Request $request, $respaldo)
    {
        $validated = $request->validate([
            'equipo_id' => 'nullable|exists:equipos,id',
            'tipo' => 'required|string',
            'ubicacion' => 'nullable|string',
            'fecha_respaldo' => 'required|date',
            'tamano' => 'nullable|string',
            'responsable' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        return parent::update($respaldo, $validated);
    }
}
