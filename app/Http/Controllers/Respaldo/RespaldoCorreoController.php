<?php

namespace App\Http\Controllers\Respaldo;
use App\Http\Controllers\Controller;

use App\Models\RespaldoCorreo;
use App\Services\Respaldo\RespaldoCorreoService;
use Illuminate\Http\Request;

class RespaldoCorreoController extends Controller
{
    public function __construct(
        protected RespaldoCorreoService $respaldoCorreoService
    ) {}

    public function index(Request $request)
    {
        return $this->respaldoCorreoService->indexData($request);
    }

    public function create()
    {
        return $this->respaldoCorreoService->createData();
    }

    public function store(Request $request)
    {
        $this->respaldoCorreoService->validateAndCreate($request);
        return redirect()->route('respaldos.correos.index')
            ->with('success', 'Respaldo de correos creado correctamente.');
    }

    public function show($respaldoCorreo)
    {
        return $this->respaldoCorreoService->showData(RespaldoCorreo::findOrFail($respaldoCorreo));
    }

    public function edit($respaldoCorreo)
    {
        return $this->respaldoCorreoService->editData(RespaldoCorreo::findOrFail($respaldoCorreo));
    }

    public function update(Request $request, $respaldoCorreo)
    {
        $respaldo = RespaldoCorreo::findOrFail($respaldoCorreo);
        $this->respaldoCorreoService->validateAndUpdate($request, $respaldo);
        return redirect()->route('respaldos.correos.index')
            ->with('success', 'Respaldo de correos actualizado correctamente.');
    }

    public function destroy($respaldoCorreo)
    {
        $respaldo = RespaldoCorreo::findOrFail($respaldoCorreo);
        $this->respaldoCorreoService->delete($respaldo);
        return redirect()->route('respaldos.correos.index')
            ->with('success', 'Respaldo de correos eliminado correctamente.');
    }

    public function download($respaldoCorreo)
    {
        $respaldo = RespaldoCorreo::findOrFail($respaldoCorreo);
        return $this->respaldoCorreoService->download($respaldo);
    }
}



