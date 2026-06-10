<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Reporte de Respaldos</title>
<style>table{width:100%;border-collapse:collapse}th,td{border:1px solid #333;padding:6px;text-align:left}th{background:#eee}</style>
</head>
<body>
<h2>Reporte de Respaldos</h2>
<table>
<thead><tr><th>Equipo</th><th>Tipo</th><th>Ubicación</th><th>Fecha</th><th>Tamaño</th><th>Responsable</th></tr></thead>
<tbody>
@foreach($respaldos as $r)
<tr><td>{{ $r->equipo->nombre_equipo ?? 'N/A' }}</td><td>{{ $r->tipo }}</td><td>{{ $r->ubicacion }}</td><td>{{ $r->fecha_respaldo }}</td><td>{{ $r->tamano }}</td><td>{{ $r->responsable }}</td></tr>
@endforeach
</tbody>
</table>
</body>
</html>
