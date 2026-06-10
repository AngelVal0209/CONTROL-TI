<?php

namespace App\Http\Controllers\Incidente;
use App\Http\Controllers\Controller;

use App\Models\Incidente;
use App\Services\Incidente\IncidenteService;
use Illuminate\Http\Request;

class IncidenteController extends Controller
{
    public function __construct(
        protected IncidenteService $incidenteService
    ) {}

    public function index(Request $request)
    {
        return $this->incidenteService->indexData($request);
    }

    public function create()
    {
        return $this->incidenteService->createData();
    }

    public function store(Request $request)
    {
        $this->incidenteService->validateAndCreate($request);
        return redirect()->route('incidentes.index')->with('success', 'Incidente registrado correctamente.');
    }

    public function show(Incidente $incidente)
    {
        return $this->incidenteService->showData($incidente);
    }

    public function edit(Incidente $incidente)
    {
        return $this->incidenteService->editData($incidente);
    }

    public function update(Request $request, Incidente $incidente)
    {
        $this->incidenteService->validateAndUpdate($request, $incidente);
        return redirect()->route('incidentes.index')->with('success', 'Incidente actualizado correctamente.');
    }

    public function destroy(Incidente $incidente)
    {
        $this->incidenteService->delete($incidente);
        return redirect()->route('incidentes.index')->with('success', 'Incidente eliminado correctamente.');
    }

    public function resolver(Incidente $incidente)
    {
        $this->incidenteService->resolver($incidente);
        return redirect()->route('incidentes.index')->with('success', 'Incidente marcado como resuelto.');
    }
}



