<?php

namespace App\Services\Incidente;
use App\Services\BaseService;

use App\Models\Equipo;
use App\Repositories\Incidente\IncidenteRepository;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncidenteService extends BaseService
{
    protected function repositoryClass(): string
    {
        return IncidenteRepository::class;
    }

    public function indexData(Request $request)
    {
        $query = $this->repository->queryWithRelations();

        if ($search = $request->get('global')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        if ($prioridad = $request->get('prioridad')) {
            $query->where('prioridad', $prioridad);
        }

        if ($equipoId = $request->get('equipo_id')) {
            $query->where('equipo_id', $equipoId);
        }

        if ($fechaDesde = $request->get('fecha_desde')) {
            $query->whereDate('fecha_reporte', '>=', $fechaDesde);
        }

        if ($fechaHasta = $request->get('fecha_hasta')) {
            $query->whereDate('fecha_reporte', '<=', $fechaHasta);
        }

        return Inertia::render('Incidentes/Index', [
            'incidentes' => $query->latest()->paginate(10)->withQueryString(),
            'equipos' => Equipo::all(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('Incidentes/Form', [
            'equipos' => Equipo::all(),
        ]);
    }

    public function editData($incidente)
    {
        $incidente->load(['equipo', 'usuarioReporta']);
        return Inertia::render('Incidentes/Form', [
            'incidente' => $incidente,
        ]);
    }

    public function showData($incidente)
    {
        $incidente->load(['equipo', 'usuarioReporta']);
        return Inertia::render('Incidentes/Show', [
            'incidente' => $incidente,
        ]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            'fecha_reporte' => 'required|date',
            'evidencias' => 'nullable|string',
        ]);

        $validated['usuario_reporta_id'] = auth()->id();
        $validated['estado'] = 'pendiente';

        return parent::create($validated);
    }

    public function validateAndUpdate(Request $request, $incidente)
    {
        $validated = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            'fecha_reporte' => 'required|date',
            'evidencias' => 'nullable|string',
            'solucion' => 'nullable|string',
        ]);

        return parent::update($incidente, $validated);
    }

    public function resolver($incidente)
    {
        $incidente->update([
            'estado' => 'resuelto',
            'fecha_resolucion' => now(),
        ]);

        AuditoriaService::registrar('resolver', 'incidentes', $incidente->id, "Incidente {$incidente->titulo} resuelto");
    }

    protected function auditDetail(string $accion, $model): string
    {
        return "Incidente {$model->titulo} {$accion}do";
    }
}


