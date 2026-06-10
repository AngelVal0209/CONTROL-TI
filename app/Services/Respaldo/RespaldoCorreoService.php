<?php

namespace App\Services\Respaldo;
use App\Services\BaseService;

use App\Repositories\Respaldo\RespaldoCorreoRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RespaldoCorreoService extends BaseService
{
    protected function repositoryClass(): string
    {
        return RespaldoCorreoRepository::class;
    }

    public function indexData(Request $request)
    {
        $query = $this->repository->query();

        if ($search = $request->get('global')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('responsable', 'like', "%{$search}%");
            });
        }

        return Inertia::render('RespaldosCorreos/Index', [
            'respaldos' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('RespaldosCorreos/Form');
    }

    public function editData($respaldo)
    {
        return Inertia::render('RespaldosCorreos/Form', [
            'respaldo' => $respaldo,
        ]);
    }

    public function showData($respaldo)
    {
        return Inertia::render('RespaldosCorreos/Show', [
            'respaldo' => $respaldo,
        ]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'archivo' => 'nullable|file|max:204800',
            'fecha_respaldo' => 'required|date',
            'responsable' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('respaldos_correos', 'local');
            $validated['archivo'] = $path;
            $validated['tamano'] = $this->formatBytes($request->file('archivo')->getSize());
        }

        return parent::create($validated);
    }

    public function validateAndUpdate(Request $request, $respaldo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'archivo' => 'nullable|file|max:204800',
            'fecha_respaldo' => 'required|date',
            'responsable' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('archivo')) {
            if ($respaldo->archivo) {
                Storage::disk('local')->delete($respaldo->archivo);
            }
            $path = $request->file('archivo')->store('respaldos_correos', 'local');
            $validated['archivo'] = $path;
            $validated['tamano'] = $this->formatBytes($request->file('archivo')->getSize());
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
        return Storage::disk('local')->download($respaldo->archivo);
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }

    protected function auditDetail(string $accion, $model): string
    {
        return "Respaldo Correo {$model->nombre} {$accion}do";
    }
}


