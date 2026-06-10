<?php

namespace App\Services\Equipo;
use App\Services\BaseService;

use App\Models\Area;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Puesto;
use App\Models\Tipo;
use App\Models\User;
use App\Repositories\Equipo\EquipoRepository;
use Illuminate\Database\Eloquent\Model;
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
        $this->seedDefaultTipos();
        $this->seedDefaultMarcas();
        $this->seedDefaultModelos();

        return [
            'areas' => Area::where('activo', true)->get(),
            'puestos' => Puesto::where('activo', true)->get(),
            'usuarios' => User::where('activo', true)->get(['id', 'name', 'documento'])->map(fn ($u) => [
                'id' => $u->id,
                'label' => "{$u->name} ({$u->documento})"
            ]),
            'tipos' => Tipo::where('activo', true)->get(),
            'marcas' => Marca::where('activo', true)->get(),
            'modelos' => Modelo::where('activo', true)->get(),
        ];
    }

    private function seedDefaultTipos(): void
    {
        if (Tipo::count() > 0) return;
        $defaults = ['Computadora', 'Laptop', 'Servidor', 'Impresora', 'Switch', 'Router', 'Monitor', 'Tablet', 'Celular', 'Otro'];
        foreach ($defaults as $nombre) {
            Tipo::create(['nombre' => $nombre]);
        }
    }

    private function seedDefaultMarcas(): void
    {
        if (Marca::count() > 0) return;
        $defaults = ['HP', 'Dell', 'Lenovo', 'Apple', 'Samsung', 'Canon', 'Epson', 'Cisco', 'Otro'];
        foreach ($defaults as $nombre) {
            Marca::create(['nombre' => $nombre]);
        }
    }

    private function seedDefaultModelos(): void
    {
        if (Modelo::count() > 0) return;
        $defaults = ['EliteBook', 'ProBook', 'ThinkPad', 'Latitude', 'OptiPlex', 'MacBook Pro', 'MacBook Air', 'IdeaPad', 'Pavilion', 'Inspiron', 'Surface Pro', 'Vostro', 'ThinkCentre', 'ProDesk', 'Otro'];
        foreach ($defaults as $nombre) {
            Modelo::create(['nombre' => $nombre]);
        }
    }

    public function indexData(Request $request)
    {
        $query = $this->repository->query()->with(['areaModel', 'tipoModel', 'marcaModel', 'modeloModel', 'usuarioRegistra']);

        if ($search = $request->get('global')) {
            $query = $this->repository->search($search)->with(['areaModel', 'tipoModel', 'marcaModel', 'modeloModel', 'usuarioRegistra']);
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
            'tipoModel',
            'marcaModel',
            'modeloModel',
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
            'tipo_id' => 'required|exists:tipos,id',
            'marca_id' => 'required|exists:marcas,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'modelo' => 'nullable|string',
            'propietario' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
            'puesto_id' => 'nullable|exists:puestos_trabajo,id',
            'estado' => 'required|string',
            'condicion' => 'required|string',
            'fecha_adquisicion' => 'nullable|date',
        ]);

        $validated['usuario_registra_id'] = auth()->id();
        $this->syncAreaAndPuesto($validated);
        $this->syncTipoAndMarca($validated);
        $this->syncModelo($validated);
        $model = $this->repository->create($validated);
        $this->logAudit('crear', $model);
        return $model;
    }

    public function validateAndUpdate(Request $request, $equipo)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:equipos,codigo,' . $equipo->id,
            'serie' => 'required|string',
            'nombre_equipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'tipo_id' => 'required|exists:tipos,id',
            'marca_id' => 'required|exists:marcas,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'modelo' => 'nullable|string',
            'propietario' => 'nullable|string',
            'area_id' => 'nullable|exists:areas,id',
            'puesto_id' => 'nullable|exists:puestos_trabajo,id',
            'estado' => 'required|string',
            'condicion' => 'required|string',
            'fecha_adquisicion' => 'nullable|date',
        ]);

        $this->syncAreaAndPuesto($validated);
        $this->syncTipoAndMarca($validated);
        $this->syncModelo($validated);
        $equipo->update($validated);
        $this->logAudit('actualizar', $equipo->fresh());
        return $equipo;
    }

    private function syncTipoAndMarca(array &$validated): void
    {
        if (!empty($validated['tipo_id'])) {
            $tipo = Tipo::find($validated['tipo_id']);
            $validated['tipo'] = $tipo?->nombre ?? '';
        }
        if (!empty($validated['marca_id'])) {
            $marca = Marca::find($validated['marca_id']);
            $validated['marca'] = $marca?->nombre ?? '';
        }
    }

    private function syncModelo(array &$validated): void
    {
        if (!empty($validated['modelo_id'])) {
            $modelo = Modelo::find($validated['modelo_id']);
            $validated['modelo'] = $modelo?->nombre ?? '';
        }
    }

    public function delete(Model $equipo): bool
    {
        return parent::delete($equipo);
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


