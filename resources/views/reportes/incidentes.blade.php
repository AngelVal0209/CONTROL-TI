<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Incidentes</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Incidentes</h1>
    <table>
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha Reporte</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidentes as $incidente)
            <tr>
                <td>{{ $incidente->equipo->nombre_equipo ?? 'N/A' }}</td>
                <td>{{ $incidente->titulo }}</td>
                <td>{{ $incidente->estado }}</td>
                <td>{{ $incidente->prioridad }}</td>
                <td>{{ $incidente->fecha_reporte->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
