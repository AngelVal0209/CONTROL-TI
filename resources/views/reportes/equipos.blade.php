<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Equipos</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Equipos</h1>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Área</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipos as $equipo)
            <tr>
                <td>{{ $equipo->codigo }}</td>
                <td>{{ $equipo->nombre_equipo }}</td>
                <td>{{ $equipo->tipo }}</td>
                <td>{{ $equipo->marca }}</td>
                <td>{{ $equipo->area }}</td>
                <td>{{ $equipo->estado }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
