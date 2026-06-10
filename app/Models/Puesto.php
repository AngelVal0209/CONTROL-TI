<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $table = 'puestos_trabajo';

    protected $fillable = ['nombre', 'descripcion', 'area_id', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'puesto_id');
    }
}
