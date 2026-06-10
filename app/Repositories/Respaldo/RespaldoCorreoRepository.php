<?php

namespace App\Repositories\Respaldo;
use App\Repositories\BaseRepository;

use App\Models\RespaldoCorreo;

class RespaldoCorreoRepository extends BaseRepository
{
    protected function model(): string
    {
        return RespaldoCorreo::class;
    }
}


