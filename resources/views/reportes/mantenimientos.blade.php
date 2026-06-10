<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Mantenimientos</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Mantenimientos</h1>
    <table>
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Tipo</th>
                <th>Fecha Programada</th>
                <th>Estado</th>
                <th>Técnico</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mantenimientos as $mantenimiento)
            <tr>
                <td>{{ $mantenimiento->equipo->nombre_equipo ?? 'N/A' }}</td>
                <td>{{ $mantenimiento->tipo }}</td>
                <td>{{ $mantenimiento->fecha_programada?->format('d/m/Y') ?? '' }}</td>
                <td>{{ $mantenimiento->estado }}</td>
                <td>{{ $mantenimiento->tecnico ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
