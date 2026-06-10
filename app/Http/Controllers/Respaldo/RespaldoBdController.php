<?php

namespace App\Http\Controllers\Respaldo;

use App\Models\RespaldoBd;
use App\Services\Respaldo\RespaldoBdService;
use Illuminate\Http\Request;

class RespaldoBdController extends Controller
{
    public function __construct(
        protected RespaldoBdService $respaldoBdService
    ) {}

    public function index(Request $request)
    {
        return $this->respaldoBdService->indexData($request);
    }

    public function create()
    {
        return $this->respaldoBdService->createData();
    }

    public function store(Request $request)
    {
        $this->respaldoBdService->validateAndCreate($request);
        return redirect()->route('respaldos.bd.index')
            ->with('success', 'Respaldo de BD creado correctamente.');
    }

    public function show($respaldoBd)
    {
        return $this->respaldoBdService
            ->setModel(RespaldoBd::findOrFail($respaldoBd))
            ->showData(RespaldoBd::findOrFail($respaldoBd));
    }

    public function edit($respaldoBd)
    {
        return $this->respaldoBdService
            ->setModel(RespaldoBd::findOrFail($respaldoBd))
            ->editData(RespaldoBd::findOrFail($respaldoBd));
    }

    public function update(Request $request, $respaldoBd)
    {
        $respaldo = RespaldoBd::findOrFail($respaldoBd);
        $this->respaldoBdService->setModel($respaldo)->validateAndUpdate($request, $respaldo);
        return redirect()->route('respaldos.bd.index')
            ->with('success', 'Respaldo de BD actualizado correctamente.');
    }

    public function destroy($respaldoBd)
    {
        $respaldo = RespaldoBd::findOrFail($respaldoBd);
        $this->respaldoBdService->setModel($respaldo)->delete($respaldo);
        return redirect()->route('respaldos.bd.index')
            ->with('success', 'Respaldo de BD eliminado correctamente.');
    }

    public function download($respaldoBd)
    {
        $respaldo = RespaldoBd::findOrFail($respaldoBd);
        return $this->respaldoBdService->setModel($respaldo)->download($respaldo);
    }
}



