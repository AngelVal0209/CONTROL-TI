<?php

namespace App\Http\Controllers\Respaldo;

use App\Models\Respaldo;
use App\Services\Respaldo\RespaldoService;
use Illuminate\Http\Request;

class RespaldoController extends Controller
{
    public function __construct(
        protected RespaldoService $respaldoService
    ) {}

    public function index(Request $request)
    {
        return $this->respaldoService->indexData($request);
    }

    public function create()
    {
        return $this->respaldoService->createData();
    }

    public function store(Request $request)
    {
        $this->respaldoService->validateAndCreate($request);
        return redirect()->route('respaldos.index')->with('success', 'Respaldo registrado correctamente.');
    }

    public function show(Respaldo $respaldo)
    {
        return $this->respaldoService->showData($respaldo);
    }

    public function edit(Respaldo $respaldo)
    {
        return $this->respaldoService->editData($respaldo);
    }

    public function update(Request $request, Respaldo $respaldo)
    {
        $this->respaldoService->validateAndUpdate($request, $respaldo);
        return redirect()->route('respaldos.index')->with('success', 'Respaldo actualizado correctamente.');
    }

    public function destroy(Respaldo $respaldo)
    {
        $this->respaldoService->delete($respaldo);
        return redirect()->route('respaldos.index')->with('success', 'Respaldo eliminado correctamente.');
    }
}



