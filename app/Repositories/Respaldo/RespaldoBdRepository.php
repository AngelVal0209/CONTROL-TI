<?php

namespace App\Repositories\Respaldo;

use App\Models\RespaldoBd;

class RespaldoBdRepository extends BaseRepository
{
    protected function model(): string
    {
        return RespaldoBd::class;
    }
}


