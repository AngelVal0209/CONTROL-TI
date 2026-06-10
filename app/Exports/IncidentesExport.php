<?php

namespace App\Exports;

use App\Models\Incidente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncidentesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Incidente::with('equipo')->get();
    }

    public function headings(): array
    {
        return [
            'Equipo',
            'Título',
            'Descripción',
            'Estado',
            'Prioridad',
            'Fecha Reporte',
            'Fecha Resolución',
        ];
    }

    public function map($incidente): array
    {
        return [
            $incidente->equipo->nombre_equipo ?? 'N/A',
            $incidente->titulo,
            $incidente->descripcion,
            $incidente->estado,
            $incidente->prioridad,
            $incidente->fecha_reporte->format('d/m/Y H:i'),
            $incidente->fecha_resolucion?->format('d/m/Y H:i') ?? '',
        ];
    }
}
