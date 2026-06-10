<?php

namespace App\Repositories;

use App\Models\Configuracion;

class ConfiguracionRepository extends BaseRepository
{
    protected function model(): string
    {
        return Configuracion::class;
    }

    public function findByEquipo(int $equipoId)
    {
        return $this->model->where('equipo_id', $equipoId)->get();
    }
}
