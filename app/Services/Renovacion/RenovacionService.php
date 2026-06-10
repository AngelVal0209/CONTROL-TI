<?php

namespace App\Services\Renovacion;

use App\Models\Renovacion;
use App\Repositories\Renovacion\RenovacionRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RenovacionService extends BaseService
{
    protected function repositoryClass(): string
    {
        return RenovacionRepository::class;
    }

    public function indexData(Request $request)
    {
        $filters = [
            'estado' => $request->get('estado'),
            'tipo' => $request->get('tipo'),
            'search' => $request->get('global'),
        ];

        $query = $this->repository->queryWithFilters($filters);

        return Inertia::render('Renovaciones/Index', [
            'renovaciones' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('Renovaciones/Form');
    }

    public function editData($renovacion)
    {
        return Inertia::render('Renovaciones/Form', [
            'renovacion' => $renovacion,
        ]);
    }

    public function showData($renovacion)
    {
        return Inertia::render('Renovaciones/Show', [
            'renovacion' => $renovacion,
        ]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'proveedor' => 'nullable|string',
            'tipo' => 'required|string',
            'monto' => 'nullable|numeric|min:0',
            'moneda' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_vencimiento' => 'required|date',
            'periodo' => 'nullable|string',
            'estado' => 'nullable|string',
            'archivo' => 'nullable|file|max:512000',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('archivo')) {
            $validated['archivo'] = $request->file('archivo')->store('renovaciones', 'local');
        }

        return parent::create($validated);
    }

    public function validateAndUpdate(Request $request, $renovacion)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'proveedor' => 'nullable|string',
            'tipo' => 'required|string',
            'monto' => 'nullable|numeric|min:0',
            'moneda' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_vencimiento' => 'required|date',
            'periodo' => 'nullable|string',
            'estado' => 'nullable|string',
            'archivo' => 'nullable|file|max:512000',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('archivo')) {
            if ($renovacion->archivo) {
                Storage::disk('local')->delete($renovacion->archivo);
            }
            $validated['archivo'] = $request->file('archivo')->store('renovaciones', 'local');
        }

        return parent::update($renovacion, $validated);
    }

    public function delete(Model $renovacion): bool
    {
        if ($renovacion->archivo) {
            Storage::disk('local')->delete($renovacion->archivo);
        }
        return parent::delete($renovacion);
    }

    public function download($renovacion)
    {
        if (!$renovacion->archivo || !Storage::disk('local')->exists($renovacion->archivo)) {
            return back()->with('error', 'Archivo no encontrado.');
        }
        return Storage::disk('local')->download($renovacion->archivo);
    }

    public function notificaciones()
    {
        $repo = $this->repository;
        $proximos = $repo->getProximosVencer(30);
        $vencidos = $repo->getVencidos();

        return response()->json([
            'proximos' => $proximos['items'],
            'vencidos' => $vencidos,
            'total' => count($proximos['items']) + count($vencidos),
        ]);
    }

    protected function auditDetail(string $accion, $model): string
    {
        return "Renovacion {$model->nombre} {$accion}da";
    }
}
