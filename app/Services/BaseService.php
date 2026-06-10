<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use App\Services\Auditoria\AuditoriaService;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected BaseRepository $repository;

    public function __construct()
    {
        $this->repository = app($this->repositoryClass());
    }

    abstract protected function repositoryClass(): string;

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Model
    {
        $model = $this->repository->create($data);
        $this->logAudit('crear', $model);
        return $model;
    }

    public function update(Model $model, array $data): bool
    {
        $updated = $this->repository->update($model, $data);
        if ($updated) {
            $this->logAudit('actualizar', $model);
        }
        return $updated;
    }

    public function delete(Model $model): bool
    {
        $deleted = $this->repository->delete($model);
        if ($deleted) {
            $this->logAudit('eliminar', $model);
        }
        return $deleted;
    }

    protected function logAudit(string $accion, Model $model): void
    {
        AuditoriaService::registrar(
            $accion,
            $model->getTable(),
            $model->id,
            $this->auditDetail($accion, $model)
        );
    }

    protected function auditDetail(string $accion, Model $model): string
    {
        $name = $model->nombre_equipo ?? $model->titulo ?? $model->name ?? 'registro';
        return ucfirst($accion) . " {$name}";
    }
}
