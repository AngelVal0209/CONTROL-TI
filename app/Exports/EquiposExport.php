<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EquiposExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Equipo::all();
    }

    public function headings(): array
    {
        return [
            'Código',
            'Serie',
            'Nombre',
            'Tipo',
            'Marca',
            'Modelo',
            'Área',
            'Estado',
            'Condición',
            'Propietario',
        ];
    }

    public function map($equipo): array
    {
        return [
            $equipo->codigo,
            $equipo->serie,
            $equipo->nombre_equipo,
            $equipo->tipo,
            $equipo->marca,
            $equipo->modelo,
            $equipo->area,
            $equipo->estado,
            $equipo->condicion,
            $equipo->propietario,
        ];
    }
}
