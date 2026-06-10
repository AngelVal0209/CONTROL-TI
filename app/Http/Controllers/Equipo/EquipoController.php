<?php

namespace App\Http\Controllers\Equipo;
use App\Http\Controllers\Controller;

use App\Models\Area;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Puesto;
use App\Models\Tipo;
use App\Services\Equipo\EquipoService;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function __construct(
        protected EquipoService $equipoService
    ) {}

    public function index(Request $request)
    {
        return $this->equipoService->indexData($request);
    }

    public function create()
    {
        return $this->equipoService->createData();
    }

    public function store(Request $request)
    {
        $this->equipoService->validateAndCreate($request);
        return redirect()->route('equipos.index')->with('success', 'Equipo creado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        return $this->equipoService->showData($equipo);
    }

    public function edit(Equipo $equipo)
    {
        return $this->equipoService->editData($equipo);
    }

    public function update(Request $request, Equipo $equipo)
    {
        $this->equipoService->validateAndUpdate($request, $equipo);
        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $this->equipoService->delete($equipo);
        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente.');
    }

    public function exportExcel()
    {
        return $this->equipoService->exportExcel();
    }

    public function storeArea(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:areas,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $area = Area::create($validated);
        return response()->json($area, 201);
    }

    public function storePuesto(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:puestos_trabajo,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $puesto = Puesto::create($validated);
        return response()->json($puesto, 201);
    }

    public function storeTipo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipos,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = Tipo::create($validated);
        return response()->json($tipo, 201);
    }

    public function storeMarca(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:marcas,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $marca = Marca::create($validated);
        return response()->json($marca, 201);
    }

    public function storeModelo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:modelos,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $modelo = Modelo::create($validated);
        return response()->json($modelo, 201);
    }
}



