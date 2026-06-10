<?php

namespace App\Http\Controllers\Renovacion;

use App\Http\Controllers\Controller;
use App\Models\Renovacion;
use App\Services\Renovacion\RenovacionService;
use Illuminate\Http\Request;

class RenovacionController extends Controller
{
    public function __construct(
        protected RenovacionService $renovacionService
    ) {}

    public function index(Request $request)
    {
        return $this->renovacionService->indexData($request);
    }

    public function create()
    {
        return $this->renovacionService->createData();
    }

    public function store(Request $request)
    {
        $this->renovacionService->validateAndCreate($request);
        return redirect()->route('renovaciones.index')
            ->with('success', 'Renovación registrada correctamente.');
    }

    public function show(Renovacion $renovacion)
    {
        return $this->renovacionService->showData($renovacion);
    }

    public function edit(Renovacion $renovacion)
    {
        return $this->renovacionService->editData($renovacion);
    }

    public function update(Request $request, Renovacion $renovacion)
    {
        $this->renovacionService->validateAndUpdate($request, $renovacion);
        return redirect()->route('renovaciones.index')
            ->with('success', 'Renovación actualizada correctamente.');
    }

    public function destroy(Renovacion $renovacion)
    {
        $this->renovacionService->delete($renovacion);
        return redirect()->route('renovaciones.index')
            ->with('success', 'Renovación eliminada correctamente.');
    }

    public function download(Renovacion $renovacion)
    {
        return $this->renovacionService->download($renovacion);
    }

    public function notificaciones()
    {
        return $this->renovacionService->notificaciones();
    }
}
