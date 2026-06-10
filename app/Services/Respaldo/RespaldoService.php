<?php

namespace App\Services\Respaldo;
use App\Services\BaseService;

use App\Models\Equipo;
use App\Repositories\Respaldo\RespaldoRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'archivo' => 'nullable|file|max:512000',
        ]);

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('respaldos', 'local');
            $validated['archivo'] = $path;
        }

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
            'archivo' => 'nullable|file|max:512000',
        ]);

        if ($request->hasFile('archivo')) {
            if ($respaldo->archivo) {
                Storage::disk('local')->delete($respaldo->archivo);
            }
            $path = $request->file('archivo')->store('respaldos', 'local');
            $validated['archivo'] = $path;
        }

        return parent::update($respaldo, $validated);
    }

    public function delete(Model $respaldo): bool
    {
        if ($respaldo->archivo) {
            Storage::disk('local')->delete($respaldo->archivo);
        }
        return parent::delete($respaldo);
    }

    public function download($respaldo)
    {
        if (!$respaldo->archivo || !Storage::disk('local')->exists($respaldo->archivo)) {
            return back()->with('error', 'Archivo no encontrado.');
        }
        $filename = $respaldo->tipo . '_' . ($respaldo->equipo?->nombre_equipo ?? 'respaldo') . '.' . pathinfo($respaldo->archivo, PATHINFO_EXTENSION);
        return Storage::disk('local')->download($respaldo->archivo, $filename);
    }
}


