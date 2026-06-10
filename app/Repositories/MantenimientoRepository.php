<?php

namespace App\Repositories;

use App\Models\Mantenimiento;

class MantenimientoRepository extends BaseRepository
{
    protected function model(): string
    {
        return Mantenimiento::class;
    }

    public function queryWithEquipo()
    {
        return $this->model->with('equipo');
    }

    public function getCounts()
    {
        return [
            'realizados' => $this->model->where('estado', 'realizado')->count(),
            'pendientes' => $this->model->where('estado', 'pendiente')->count(),
        ];
    }
}
