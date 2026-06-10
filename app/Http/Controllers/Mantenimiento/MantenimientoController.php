<?php

namespace App\Http\Controllers\Mantenimiento;
use App\Http\Controllers\Controller;

use App\Models\Mantenimiento;
use App\Services\Mantenimiento\MantenimientoService;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function __construct(
        protected MantenimientoService $mantenimientoService
    ) {}

    public function index(Request $request)
    {
        return $this->mantenimientoService->indexData($request);
    }

    public function create()
    {
        return $this->mantenimientoService->createData();
    }

    public function store(Request $request)
    {
        $this->mantenimientoService->validateAndCreate($request);
        return redirect()->route('mantenimientos.index')->with('success', 'Mantenimiento registrado correctamente.');
    }

    public function show(Mantenimiento $mantenimiento)
    {
        return $this->mantenimientoService->showData($mantenimiento);
    }

    public function edit(Mantenimiento $mantenimiento)
    {
        return $this->mantenimientoService->editData($mantenimiento);
    }

    public function update(Request $request, Mantenimiento $mantenimiento)
    {
        $this->mantenimientoService->validateAndUpdate($request, $mantenimiento);
        return redirect()->route('mantenimientos.index')->with('success', 'Mantenimiento actualizado correctamente.');
    }

    public function destroy(Mantenimiento $mantenimiento)
    {
        $this->mantenimientoService->delete($mantenimiento);
        return redirect()->route('mantenimientos.index')->with('success', 'Mantenimiento eliminado correctamente.');
    }
}



