<?php

namespace App\Repositories;

use App\Models\Auditoria;

class AuditoriaRepository extends BaseRepository
{
    protected function model(): string
    {
        return Auditoria::class;
    }

    public function queryWithUser()
    {
        return $this->model->with('usuario');
    }
}
