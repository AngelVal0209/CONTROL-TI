<?php

namespace App\Services\Dashboard;

use App\Models\Equipo;
use App\Models\Incidente;
use App\Models\Mantenimiento;
use App\Models\Respaldo;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardService
{
    public function indexData()
    {
        $totalEquipos = Equipo::count();
        $equiposActivos = Equipo::where('estado', 'operativo')->count();
        $equiposInactivos = Equipo::where('estado', 'inactivo')->count();
        $equiposMantenimiento = Equipo::where('estado', 'en mantenimiento')->count();
        $equiposBaja = Equipo::where('estado', 'de baja')->count();

        $incidentesPendientes = Incidente::where('estado', 'pendiente')->count();
        $incidentesEnProceso = Incidente::where('estado', 'en_proceso')->count();
        $incidentesResueltos = Incidente::where('estado', 'resuelto')->count();

        $respaldosTotales = Respaldo::count();
        $respaldosEsteMes = Respaldo::whereMonth('created_at', now()->month)->count();

        $mantenimientosPendientes = Mantenimiento::where('estado', 'pendiente')->count();
        $mantenimientosRealizados = Mantenimiento::where('estado', 'realizado')->count();

        $kpis = $this->calculateKPIs($totalEquipos, $mantenimientosRealizados, $mantenimientosPendientes, $respaldosTotales, $respaldosEsteMes);

        return Inertia::render('Dashboard/Index', [
            'totalEquipos' => $totalEquipos,
            'equiposActivos' => $equiposActivos,
            'equiposInactivos' => $equiposInactivos,
            'equiposMantenimiento' => $equiposMantenimiento,
            'equiposBaja' => $equiposBaja,
            'incidentesPendientes' => $incidentesPendientes,
            'incidentesEnProceso' => $incidentesEnProceso,
            'incidentesResueltos' => $incidentesResueltos,
            'respaldos' => $respaldosTotales,
            'mantenimientos' => $mantenimientosRealizados,
            'kpis' => $kpis,
        ]);
    }

    public function kpisData()
    {
        $totalEquipos = Equipo::count();
        $mantenimientosRealizados = Mantenimiento::where('estado', 'realizado')->count();
        $mantenimientosPendientes = Mantenimiento::where('estado', 'pendiente')->count();
        $respaldosTotales = Respaldo::count();
        $respaldosEsteMes = Respaldo::whereMonth('created_at', now()->month)->count();

        $kpis = $this->calculateKPIs($totalEquipos, $mantenimientosRealizados, $mantenimientosPendientes, $respaldosTotales, $respaldosEsteMes);

        return Inertia::render('Kpis/Index', [
            'kpis' => $kpis,
        ]);
    }

    private function calculateKPIs($totalEquipos, $mantenimientosRealizados, $mantenimientosPendientes, $respaldosTotales, $respaldosEsteMes): array
    {
        $equiposActivos = Equipo::where('estado', 'operativo')->count();
        $equiposInactivos = Equipo::where('estado', 'inactivo')->count();

        return [
            'inventario' => [
                'total' => $totalEquipos,
                'operativos' => $equiposActivos,
                'inactivos' => $equiposInactivos,
                'porcentaje_activo' => $totalEquipos > 0 ? round(($equiposActivos / $totalEquipos) * 100, 1) : 0,
                'estados' => [
                    ['label' => 'Operativos', 'value' => $equiposActivos],
                    ['label' => 'Inactivos', 'value' => $equiposInactivos],
                    ['label' => 'En Mantenimiento', 'value' => Equipo::where('estado', 'en mantenimiento')->count()],
                    ['label' => 'De Baja', 'value' => Equipo::where('estado', 'de baja')->count()],
                ],
            ],
            'incidentes' => [
                'tiempo_promedio' => $this->getTiempoPromedioResolucion(),
                'por_mes' => Incidente::whereYear('created_at', now()->year)->count(),
                'por_area' => DB::table('incidentes')
                    ->join('equipos', 'incidentes.equipo_id', '=', 'equipos.id')
                    ->count('incidentes.id'),
                'tasa_resolucion' => $this->getTasaResolucion(),
                'estados' => [
                    ['label' => 'Pendientes', 'value' => Incidente::where('estado', 'pendiente')->count()],
                    ['label' => 'En Proceso', 'value' => Incidente::where('estado', 'en_proceso')->count()],
                    ['label' => 'Resueltos', 'value' => Incidente::where('estado', 'resuelto')->count()],
                ],
                'tendencias' => $this->getIncidentesMensuales(),
            ],
            'mantenimiento' => [
                'porcentaje' => $totalEquipos > 0 ? round(($mantenimientosRealizados / $totalEquipos) * 100, 1) : 0,
                'pendientes' => $mantenimientosPendientes,
                'realizados' => $mantenimientosRealizados,
                'estados' => [
                    ['label' => 'Realizados', 'value' => $mantenimientosRealizados],
                    ['label' => 'Pendientes', 'value' => $mantenimientosPendientes],
                ],
                'tendencias' => $this->getMantenimientosMensuales(),
            ],
            'respaldo' => [
                'porcentaje' => $totalEquipos > 0 ? round(($respaldosTotales / $totalEquipos) * 100, 1) : 0,
                'ultimo' => Respaldo::latest('fecha_respaldo')->value('fecha_respaldo'),
                'por_mes' => $respaldosEsteMes,
                'tendencias' => $this->getRespaldosMensuales(),
            ],
        ];
    }

    private function getIncidentesMensuales(): array
    {
        $meses = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mes = $date->locale('es')->isoFormat('MMM');
            $meses[] = $mes;
            $data[] = Incidente::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        return ['labels' => $meses, 'data' => $data];
    }

    private function getMantenimientosMensuales(): array
    {
        $meses = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mes = $date->locale('es')->isoFormat('MMM');
            $meses[] = $mes;
            $data[] = Mantenimiento::whereYear('fecha_programada', $date->year)
                ->whereMonth('fecha_programada', $date->month)
                ->count();
        }
        return ['labels' => $meses, 'data' => $data];
    }

    private function getRespaldosMensuales(): array
    {
        $meses = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mes = $date->locale('es')->isoFormat('MMM');
            $meses[] = $mes;
            $data[] = Respaldo::whereYear('fecha_respaldo', $date->year)
                ->whereMonth('fecha_respaldo', $date->month)
                ->count();
        }
        return ['labels' => $meses, 'data' => $data];
    }

    private function getTiempoPromedioResolucion()
    {
        $promedio = Incidente::whereNotNull('fecha_resolucion')
            ->selectRaw('AVG((julianday(fecha_resolucion) - julianday(created_at)) * 24) as promedio')
            ->value('promedio');

        if (!$promedio) return 'N/A';

        $hours = floor($promedio);
        $minutes = round(($promedio - $hours) * 60);
        return "{$hours}h {$minutes}m";
    }

    private function getTasaResolucion()
    {
        $total = Incidente::count();
        if ($total === 0) return 0;
        $resueltos = Incidente::where('estado', 'resuelto')->count();
        return round(($resueltos / $total) * 100, 1);
    }
}


