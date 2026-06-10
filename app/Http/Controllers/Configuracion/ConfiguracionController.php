<?php

namespace App\Http\Controllers\Configuracion;
use App\Http\Controllers\Controller;

use App\Models\Configuracion;
use App\Models\Equipo;
use App\Services\Configuracion\ConfiguracionService;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function __construct(
        protected ConfiguracionService $configuracionService
    ) {}

    public function index(Equipo $equipo)
    {
        return $this->configuracionService->indexData($equipo);
    }

    public function create(Equipo $equipo)
    {
        return $this->configuracionService->createData($equipo);
    }

    public function store(Request $request, Equipo $equipo)
    {
        $this->configuracionService->validateAndCreate($request, $equipo);
        return redirect()->route('equipos.configuraciones.index', $equipo)->with('success', 'ConfiguraciÃƒÆ’Ã‚Â³n creada correctamente.');
    }

    public function edit(Equipo $equipo, Configuracion $configuracion)
    {
        return $this->configuracionService->editData($equipo, $configuracion);
    }

    public function update(Request $request, Equipo $equipo, Configuracion $configuracion)
    {
        $this->configuracionService->validateAndUpdate($request, $equipo, $configuracion);
        return redirect()->route('equipos.configuraciones.index', $equipo)->with('success', 'ConfiguraciÃƒÆ’Ã‚Â³n actualizada correctamente.');
    }

    public function destroy(Equipo $equipo, Configuracion $configuracion)
    {
        $this->configuracionService->delete($configuracion);
        return redirect()->route('equipos.configuraciones.index', $equipo)->with('success', 'ConfiguraciÃƒÆ’Ã‚Â³n eliminada correctamente.');
    }

    public function historial(Configuracion $configuracion)
    {
        return $this->configuracionService->historialData($configuracion);
    }
}



