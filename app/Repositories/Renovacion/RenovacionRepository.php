<?php

namespace App\Repositories\Renovacion;

use App\Models\Renovacion;
use App\Repositories\BaseRepository;

class RenovacionRepository extends BaseRepository
{
    protected function model(): string
    {
        return Renovacion::class;
    }

    public function queryWithFilters(array $filters = [])
    {
        $query = $this->model->query();

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }

        if (!empty($filters['proveedor'])) {
            $query->where('proveedor', 'like', "%{$filters['proveedor']}%");
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nombre', 'like', "%{$filters['search']}%")
                  ->orWhere('proveedor', 'like', "%{$filters['search']}%");
            });
        }

        return $query;
    }

    public function getProximosVencer(int $dias = 30): array
    {
        $hoy = now()->startOfDay();
        $limite = now()->addDays($dias)->endOfDay();

        $items = $this->model
            ->whereBetween('fecha_vencimiento', [$hoy, $limite])
            ->where('estado', 'activo')
            ->orderBy('fecha_vencimiento')
            ->get()
            ->toArray();

        $count = $this->model
            ->where('fecha_vencimiento', '<', $hoy)
            ->where('estado', 'activo')
            ->count();

        return [
            'items' => $items,
            'vencidos' => $count,
        ];
    }

    public function getVencidos(): array
    {
        $items = $this->model
            ->where('fecha_vencimiento', '<', now()->startOfDay())
            ->where('estado', 'activo')
            ->orderBy('fecha_vencimiento')
            ->get()
            ->toArray();

        return $items;
    }
}
