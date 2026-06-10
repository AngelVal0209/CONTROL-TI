<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Equipo;
use App\Models\Puesto;
use App\Repositories\EquipoRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquiposExport;

class EquipoService extends BaseService
{
    protected function repositoryClass(): string
    {
        return EquipoRepository::class;
    }

    public function getFormOptions()
    {
        return [
            'areas' => Area::where('activo', true)->get(),
            'puestos' => Puesto::where('activo', true)->get(),
            'tipos' => collect(['Computadora', 'Laptop', 'Servidor', 'Impresora', 'Switch', 'Router', 'Monitor', 'Tablet', 'Celular', 'Otro']),
            'marcas' => collect(['HP', 'Dell', 'Lenovo', 'Apple', 'Samsung', 'Canon', 'Epson', 'Cisco', 'Otro']),
        ];
    }

    public function indexData(Request $request)
    {
        $query = $this->repository->query()->with(['areaModel', 'usuarioRegistra']);

        if ($search = $request->get('global')) {
            $query = $this->repository->search($search);
        }

        if ($areaId = $request->get('area_id')) {
            $query->where('area_id', $areaId);
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        return Inertia::render('Equipos/Index', [
            'equipos' => $query->latest()->paginate(10)->withQueryString(),
            'areas' => Area::where('activo', true)->get(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('Equipos/Form', $this->getFormOptions());
    }

    public function editData($equipo)
    {
        return Inertia::render('Equipos/Form', array_merge(
            ['equipo' => $equipo],
            $this->getFormOptions()
        ));
    }

    public function showData($equipo)
    {
        $equipo->load([
            'incidentes',
            'configuracion',
            'mantenimientos',
            'respaldos',
            'areaModel',
            'puestoModel',
            'usuarioRegistra',
        ]);
        return Inertia::render('Equipos/Show', ['equipo' => $equipo]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:equipos,codigo',
            'serie' => 'required|string',
            'nombre_equipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'nullable|string',
            'propietario' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
            'puesto_id' => 'nullable|exists:puestos_trabajo,id',
            'estado' => 'required|string',
            'condicion' => 'required|string',
            'fecha_adquisicion' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['usuario_registra_id'] = auth()->id();
        $this->syncAreaAndPuesto($validated);
        return $this->repository->create($validated);
    }

    public function validateAndUpdate(Request $request, $equipo)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:equipos,codigo,' . $equipo->id,
            'serie' => 'required|string',
            'nombre_equipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'nullable|string',
            'propietario' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
            'puesto_id' => 'nullable|exists:puestos_trabajo,id',
            'estado' => 'required|string',
            'condicion' => 'required|string',
            'fecha_adquisicion' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $this->syncAreaAndPuesto($validated);
        $equipo->update($validated);
        AuditoriaService::registrar('actualizar', 'equipos', $equipo->id, "Equipo {$equipo->nombre_equipo} actualizado");
        return $equipo;
    }

    private function syncAreaAndPuesto(array &$validated): void
    {
        if (!empty($validated['area_id'])) {
            $area = Area::find($validated['area_id']);
            $validated['area'] = $area?->nombre ?? '';
        } else {
            $validated['area'] = $validated['area'] ?? '';
        }
        if (!empty($validated['puesto_id'])) {
            $puesto = Puesto::find($validated['puesto_id']);
            $validated['puesto_trabajo'] = $puesto?->nombre ?? '';
        } else {
            $validated['puesto_trabajo'] = $validated['puesto_trabajo'] ?? '';
        }
    }

    public function exportExcel()
    {
        return Excel::download(new EquiposExport, 'equipos.xlsx');
    }

    protected function auditDetail(string $accion, $model): string
    {
        return "Equipo {$model->nombre_equipo} {$accion}do";
    }
}
