<?php

namespace App\Exports;

use App\Models\Mantenimiento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MantenimientosExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Mantenimiento::with('equipo')->get();
    }

    public function headings(): array
    {
        return [
            'Equipo',
            'Tipo',
            'Fecha Programada',
            'Fecha Realizada',
            'Técnico',
            'Descripción',
            'Costo',
            'Estado',
        ];
    }

    public function map($mantenimiento): array
    {
        return [
            $mantenimiento->equipo->nombre_equipo ?? 'N/A',
            $mantenimiento->tipo,
            $mantenimiento->fecha_programada?->format('d/m/Y') ?? '',
            $mantenimiento->fecha_realizada?->format('d/m/Y') ?? '',
            $mantenimiento->tecnico ?? '',
            $mantenimiento->descripcion ?? '',
            $mantenimiento->costo ? "$" . number_format($mantenimiento->costo, 2) : '',
            $mantenimiento->estado,
        ];
    }
}
