<?php

namespace App\Services;

use App\Models\Equipo;
use App\Repositories\ConfiguracionRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfiguracionService extends BaseService
{
    protected function repositoryClass(): string
    {
        return ConfiguracionRepository::class;
    }

    public function indexData(Equipo $equipo)
    {
        return Inertia::render('Configuraciones/Index', [
            'configuraciones' => $this->repository->findByEquipo($equipo->id),
            'equipo' => $equipo,
        ]);
    }

    public function createData(Equipo $equipo)
    {
        return Inertia::render('Configuraciones/Form', [
            'equipo' => $equipo,
        ]);
    }

    public function editData(Equipo $equipo, $configuracion)
    {
        return Inertia::render('Configuraciones/Form', [
            'configuracion' => $configuracion,
            'equipo' => $equipo,
        ]);
    }

    public function historialData($configuracion)
    {
        $configuracion->load('historial.usuario');
        return Inertia::render('Configuraciones/Historial', [
            'configuracion' => $configuracion,
            'historial' => $configuracion->historial()->latest()->paginate(10),
        ]);
    }

    public function validateAndCreate(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'sistema_operativo' => 'nullable|string',
            'version' => 'nullable|string',
            'office' => 'nullable|string',
            'antivirus' => 'nullable|string',
            'cpu' => 'nullable|string',
            'ram' => 'nullable|string',
            'disco' => 'nullable|string',
            'fecha_actualizacion' => 'nullable|date',
        ]);

        $validated['equipo_id'] = $equipo->id;
        return parent::create($validated);
    }

    public function validateAndUpdate(Request $request, Equipo $equipo, $configuracion)
    {
        $validated = $request->validate([
            'sistema_operativo' => 'nullable|string',
            'version' => 'nullable|string',
            'office' => 'nullable|string',
            'antivirus' => 'nullable|string',
            'cpu' => 'nullable|string',
            'ram' => 'nullable|string',
            'disco' => 'nullable|string',
            'fecha_actualizacion' => 'nullable|date',
        ]);

        return parent::update($configuracion, $validated);
    }

    protected function auditDetail(string $accion, $model): string
    {
        return "Configuración {$accion}da para equipo #{$model->equipo_id}";
    }
}
