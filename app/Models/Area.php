<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'area_id');
    }

    public function puestos()
    {
        return $this->hasMany(Puesto::class);
    }
}
