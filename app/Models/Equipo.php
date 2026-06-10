<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo',
        'serie',
        'nombre_equipo',
        'descripcion',
        'tipo',
        'marca',
        'modelo',
        'propietario',
        'area',
        'area_id',
        'puesto_trabajo',
        'puesto_id',
        'estado',
        'condicion',
        'fecha_adquisicion',
        'observaciones',
        'usuario_registra_id',
    ];

    public function incidentes(): HasMany
    {
        return $this->hasMany(Incidente::class);
    }

    public function configuracion(): HasOne
    {
        return $this->hasOne(Configuracion::class);
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class);
    }

    public function respaldos(): HasMany
    {
        return $this->hasMany(Respaldo::class);
    }

    public function usuarioRegistra()
    {
        return $this->belongsTo(User::class, 'usuario_registra_id');
    }

    public function areaModel()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function puestoModel()
    {
        return $this->belongsTo(Puesto::class, 'puesto_id');
    }
}
