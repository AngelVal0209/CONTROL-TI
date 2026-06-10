<?php

namespace App\Http\Controllers\Reporte;

use App\Models\Equipo;
use App\Models\Incidente;
use App\Models\Mantenimiento;
use App\Models\Respaldo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquiposExport;
use App\Exports\IncidentesExport;
use App\Exports\MantenimientosExport;

class ReporteController extends Controller
{
    public function index()
    {
        return Inertia::render('Reportes/Index');
    }

    public function pdf($tipo)
    {
        $data = $this->getReportData($tipo);
        $pdf = Pdf::loadView("reportes.{$tipo}", $data);
        return $pdf->download("reporte_{$tipo}.pdf");
    }

    public function excel($tipo)
    {
        $exports = [
            'equipos' => EquiposExport::class,
            'incidentes' => IncidentesExport::class,
            'mantenimientos' => MantenimientosExport::class,
        ];

        if (!isset($exports[$tipo])) {
            return back()->with('error', 'Tipo de exportaciÃ³n no vÃ¡lido.');
        }

        return Excel::download(new $exports[$tipo], "{$tipo}.xlsx");
    }

    private function getReportData($tipo): array
    {
        return match ($tipo) {
            'inventario-general' => [
                'titulo' => 'Inventario General de Equipos',
                'equipos' => Equipo::all(),
            ],
            'inventario-area' => [
                'titulo' => 'Inventario de Equipos por Ãrea',
                'equipos' => Equipo::all()->groupBy('area'),
            ],
            'incidentes' => [
                'titulo' => 'Reporte de Incidentes',
                'incidentes' => Incidente::with('equipo')->latest()->get(),
            ],
            'mantenimientos' => [
                'titulo' => 'Reporte de Mantenimientos',
                'mantenimientos' => Mantenimiento::with('equipo')->latest()->get(),
            ],
            'respaldos' => [
                'titulo' => 'Reporte de Respaldos',
                'respaldos' => Respaldo::with('equipo')->latest()->get(),
            ],
            default => [],
        };
    }
}


