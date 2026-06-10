<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        h2 { margin-top: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    @foreach($equipos as $area => $items)
    <h2>{{ $area }}</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $equipo)
            <tr>
                <td>{{ $equipo->codigo }}</td>
                <td>{{ $equipo->nombre_equipo }}</td>
                <td>{{ $equipo->tipo }}</td>
                <td>{{ $equipo->marca }}</td>
                <td>{{ $equipo->estado }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
</body>
</html>
