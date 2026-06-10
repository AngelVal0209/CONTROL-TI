<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'tipo_id',
        'marca',
        'marca_id',
        'modelo',
        'modelo_id',
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

    protected function casts(): array
    {
        return [
            'fecha_adquisicion' => 'date',
        ];
    }

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

    public function usuarioRegistra(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_registra_id');
    }

    public function areaModel(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function puestoModel(): BelongsTo
    {
        return $this->belongsTo(Puesto::class, 'puesto_id');
    }

    public function tipoModel(): BelongsTo
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function marcaModel(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function modeloModel(): BelongsTo
    {
        return $this->belongsTo(Modelo::class, 'modelo_id');
    }
}
