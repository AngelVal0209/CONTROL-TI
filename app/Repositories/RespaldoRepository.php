<?php

namespace App\Repositories;

use App\Models\Respaldo;

class RespaldoRepository extends BaseRepository
{
    protected function model(): string
    {
        return Respaldo::class;
    }

    public function queryWithEquipo()
    {
        return $this->model->with('equipo');
    }

    public function getCounts()
    {
        return [
            'total' => $this->model->count(),
            'este_mes' => $this->model->whereMonth('created_at', now()->month)->count(),
            'ultimo' => $this->model->latest('fecha_respaldo')->value('fecha_respaldo'),
        ];
    }
}
