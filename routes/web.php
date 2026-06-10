<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\RespaldoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuditoriaController;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'store']);
});

Route::post('logout', [AuthController::class, 'destroy'])->name('logout')->middleware('auth');

// App
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Areas & Puestos quick create
    Route::post('/equipos/areas', [EquipoController::class, 'storeArea'])->name('equipos.areas.store');
    Route::post('/equipos/puestos', [EquipoController::class, 'storePuesto'])->name('equipos.puestos.store');

    Route::resource('equipos', EquipoController::class);
    Route::get('/equipos/export/excel', [EquipoController::class, 'exportExcel'])->name('equipos.export.excel');

    Route::resource('incidentes', IncidenteController::class);
    Route::patch('/incidentes/{incidente}/resolver', [IncidenteController::class, 'resolver'])->name('incidentes.resolver');

    Route::resource('equipos.configuraciones', ConfiguracionController::class)->parameters([
        'configuraciones' => 'configuracion',
    ]);
    Route::get('/configuraciones/{configuracion}/historial', [ConfiguracionController::class, 'historial'])->name('configuraciones.historial');

    Route::resource('mantenimientos', MantenimientoController::class);

    Route::resource('respaldos', RespaldoController::class);

    Route::resource('usuarios', UsuarioController::class)->middleware('role:Administrador');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/pdf/{tipo}', [ReporteController::class, 'pdf'])->name('reportes.pdf');
    Route::get('reportes/excel/{tipo}', [ReporteController::class, 'excel'])->name('reportes.excel');

    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');

    Route::get('/kpis', [DashboardController::class, 'kpis'])->name('kpis.index');
});
