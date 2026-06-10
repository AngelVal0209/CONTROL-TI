<?php

namespace App\Repositories;

use App\Models\RespaldoCorreo;

class RespaldoCorreoRepository extends BaseRepository
{
    protected function model(): string
    {
        return RespaldoCorreo::class;
    }
}
