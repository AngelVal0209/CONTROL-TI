<?php

namespace App\Services\Mantenimiento;

use App\Models\Equipo;
use App\Repositories\Mantenimiento\MantenimientoRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MantenimientoService extends BaseService
{
    protected function repositoryClass(): string
    {
        return MantenimientoRepository::class;
    }

    public function indexData(Request $request)
    {
        $query = $this->repository->queryWithEquipo();

        if ($search = $request->get('global')) {
            $query->where(function ($q) use ($search) {
                $q->where('tecnico', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($tipo = $request->get('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        if ($fechaDesde = $request->get('fecha_desde')) {
            $query->whereDate('fecha_programada', '>=', $fechaDesde);
        }

        if ($fechaHasta = $request->get('fecha_hasta')) {
            $query->whereDate('fecha_programada', '<=', $fechaHasta);
        }

        return Inertia::render('Mantenimientos/Index', [
            'mantenimientos' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('Mantenimientos/Form', [
            'equipos' => Equipo::all(),
        ]);
    }

    public function editData($mantenimiento)
    {
        $mantenimiento->load('equipo');
        return Inertia::render('Mantenimientos/Form', [
            'mantenimiento' => $mantenimiento,
            'equipos' => Equipo::all(),
        ]);
    }

    public function showData($mantenimiento)
    {
        $mantenimiento->load('equipo');
        return Inertia::render('Mantenimientos/Show', [
            'mantenimiento' => $mantenimiento,
        ]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'tipo' => 'required|string',
            'fecha_programada' => 'nullable|date',
            'fecha_realizada' => 'nullable|date',
            'tecnico' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'costo' => 'nullable|numeric|min:0',
            'estado' => 'required|string',
        ]);

        return parent::create($validated);
    }

    public function validateAndUpdate(Request $request, $mantenimiento)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'tipo' => 'required|string',
            'fecha_programada' => 'nullable|date',
            'fecha_realizada' => 'nullable|date',
            'tecnico' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'costo' => 'nullable|numeric|min:0',
            'estado' => 'required|string',
        ]);

        return parent::update($mantenimiento, $validated);
    }
}


