<?php

namespace App\Repositories\Incidente;
use App\Repositories\BaseRepository;

use App\Models\Incidente;

class IncidenteRepository extends BaseRepository
{
    protected function model(): string
    {
        return Incidente::class;
    }

    public function queryWithRelations()
    {
        return $this->model->with(['equipo', 'usuarioReporta']);
    }

    public function getCounts()
    {
        return [
            'pendientes' => $this->model->where('estado', 'pendiente')->count(),
            'en_proceso' => $this->model->where('estado', 'en_proceso')->count(),
            'resueltos' => $this->model->where('estado', 'resuelto')->count(),
        ];
    }

    public function getKPIData()
    {
        return [
            'tiempo_promedio' => $this->model->whereNotNull('fecha_resolucion')
                ->selectRaw('AVG((julianday(fecha_resolucion) - julianday(created_at)) * 24) as promedio')
                ->value('promedio') ?? 0,
            'por_mes' => $this->model->whereYear('created_at', now()->year)->count(),
            'por_area' => $this->model->join('equipos', 'incidentes.equipo_id', '=', 'equipos.id')
                ->count('incidentes.id'),
        ];
    }
}


