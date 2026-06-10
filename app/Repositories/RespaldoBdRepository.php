<?php

namespace App\Repositories;

use App\Models\RespaldoBd;

class RespaldoBdRepository extends BaseRepository
{
    protected function model(): string
    {
        return RespaldoBd::class;
    }
}
