<?php

namespace App\Repositories\Equipo;

use App\Models\Equipo;

class EquipoRepository extends BaseRepository
{
    protected function model(): string
    {
        return Equipo::class;
    }

    public function getDistinctAreas()
    {
        return $this->model->select('area')->distinct()->pluck('area');
    }

    public function search(string $search)
    {
        return $this->model->where(function ($q) use ($search) {
            $q->where('nombre_equipo', 'like', "%{$search}%")
              ->orWhere('codigo', 'like', "%{$search}%")
              ->orWhere('serie', 'like', "%{$search}%")
              ->orWhere('area', 'like', "%{$search}%");
        });
    }

    public function getCounts()
    {
        return [
            'total' => $this->model->count(),
            'operativos' => $this->model->where('estado', 'operativo')->count(),
            'inactivos' => $this->model->where('estado', 'inactivo')->count(),
        ];
    }
}


