<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Equipo\EquipoController;
use App\Http\Controllers\Incidente\IncidenteController;
use App\Http\Controllers\Configuracion\ConfiguracionController;
use App\Http\Controllers\Mantenimiento\MantenimientoController;
use App\Http\Controllers\Respaldo\RespaldoController;
use App\Http\Controllers\Respaldo\RespaldoCorreoController;
use App\Http\Controllers\Respaldo\RespaldoBdController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Reporte\ReporteController;
use App\Http\Controllers\Auditoria\AuditoriaController;
use App\Http\Controllers\Backup\BackupController;

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

    // Respaldos de Correos (antes del resource para evitar conflicto)
    Route::get('/respaldos/correos', [RespaldoCorreoController::class, 'index'])->name('respaldos.correos.index');
    Route::get('/respaldos/correos/create', [RespaldoCorreoController::class, 'create'])->name('respaldos.correos.create');
    Route::post('/respaldos/correos', [RespaldoCorreoController::class, 'store'])->name('respaldos.correos.store');
    Route::get('/respaldos/correos/{respaldoCorreo}', [RespaldoCorreoController::class, 'show'])->name('respaldos.correos.show');
    Route::get('/respaldos/correos/{respaldoCorreo}/edit', [RespaldoCorreoController::class, 'edit'])->name('respaldos.correos.edit');
    Route::put('/respaldos/correos/{respaldoCorreo}', [RespaldoCorreoController::class, 'update'])->name('respaldos.correos.update');
    Route::delete('/respaldos/correos/{respaldoCorreo}', [RespaldoCorreoController::class, 'destroy'])->name('respaldos.correos.destroy');
    Route::get('/respaldos/correos/{respaldoCorreo}/download', [RespaldoCorreoController::class, 'download'])->name('respaldos.correos.download');

    // Respaldos de BD (antes del resource para evitar conflicto)
    Route::get('/respaldos/bd', [RespaldoBdController::class, 'index'])->name('respaldos.bd.index');
    Route::get('/respaldos/bd/create', [RespaldoBdController::class, 'create'])->name('respaldos.bd.create');
    Route::post('/respaldos/bd', [RespaldoBdController::class, 'store'])->name('respaldos.bd.store');
    Route::get('/respaldos/bd/{respaldoBd}', [RespaldoBdController::class, 'show'])->name('respaldos.bd.show');
    Route::get('/respaldos/bd/{respaldoBd}/edit', [RespaldoBdController::class, 'edit'])->name('respaldos.bd.edit');
    Route::put('/respaldos/bd/{respaldoBd}', [RespaldoBdController::class, 'update'])->name('respaldos.bd.update');
    Route::delete('/respaldos/bd/{respaldoBd}', [RespaldoBdController::class, 'destroy'])->name('respaldos.bd.destroy');
    Route::get('/respaldos/bd/{respaldoBd}/download', [RespaldoBdController::class, 'download'])->name('respaldos.bd.download');

    Route::resource('respaldos', RespaldoController::class);

    // Backup completo del sistema
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index')->middleware('role:Administrador');
    Route::get('/backup/export', [BackupController::class, 'export'])->name('backup.export')->middleware('role:Administrador');
    Route::post('/backup/import', [BackupController::class, 'import'])->name('backup.import')->middleware('role:Administrador');

    Route::resource('usuarios', UsuarioController::class)->middleware('role:Administrador');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/pdf/{tipo}', [ReporteController::class, 'pdf'])->name('reportes.pdf');
    Route::get('reportes/excel/{tipo}', [ReporteController::class, 'excel'])->name('reportes.excel');

    Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');

    Route::get('/kpis', [DashboardController::class, 'kpis'])->name('kpis.index');
});
