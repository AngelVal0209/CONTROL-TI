
# Sistema de Gestión de TICs – Implementación y Control

## 1. Objetivo
Implementar un sistema de gestión y control de Tecnologías de la Información y Comunicaciones (TICs) que permita administrar inventarios, incidentes, mantenimiento preventivo/correctivo, respaldos de información y control de versiones de hardware/software.

## 2. Alcance
El sistema deberá cubrir:

- Inventario de equipos TIC.
- Registro y seguimiento de incidentes.
- Control de versiones de hardware y software.
- Gestión de mantenimiento preventivo y correctivo.
- Gestión y monitoreo de respaldos de información.
- Generación de indicadores (KPIs) y reportes.

---

# 3. Módulos Requeridos

## 3.1 Inventario de Equipos
Basado en: **MTL-IT-PR-01.F01 – Inventario Equipos**

### Funcionalidades
- Registro de activos TIC.
- Código único por equipo.
- Marca, modelo y número de serie.
- Usuario responsable.
- Área de asignación.
- Estado del equipo.
- Fecha de adquisición.
- Historial de movimientos.

### Reportes
- Inventario general.
- Equipos por área.
- Equipos por estado.
- Equipos próximos a renovación.

---

## 3.2 Registro de Incidentes
Basado en: **MTL-IT-PR-01.F02 – Registro de Incidentes**

### Funcionalidades
- Registro de incidentes.
- Clasificación por prioridad.
- Fecha y hora de ocurrencia.
- Responsable asignado.
- Estado del incidente.
- Tiempo de atención.
- Tiempo de cierre.
- Evidencias adjuntas.

### Estados sugeridos
- Abierto
- En proceso
- Pendiente
- Cerrado

### KPIs
- Incidentes abiertos.
- Incidentes cerrados.
- Tiempo promedio de atención.
- Tiempo promedio de resolución.

---

## 3.3 Control de Versiones de Hardware y Software
Basado en: **MTL-IT-PR-01.F03 – Versiones de Software y Configuraciones de Hardware**

### Funcionalidades
- Registro de hardware instalado.
- Registro de software instalado.
- Control de versiones.
- Fecha de actualización.
- Responsable del cambio.
- Historial de modificaciones.
- Trazabilidad de configuraciones.

### Reportes
- Versiones instaladas.
- Equipos desactualizados.
- Historial de cambios.

---

## 3.4 Mantenimiento Preventivo y Correctivo
Basado en: **KPIs Mantenimiento Preventivo y Correctivo de TICs**

### Funcionalidades
- Programación de mantenimientos.
- Cronograma de actividades.
- Registro de ejecución.
- Control de observaciones.
- Evidencias de mantenimiento.
- Seguimiento de cumplimiento.

### Indicadores
- % de mantenimientos ejecutados.
- % de cumplimiento del cronograma.
- Equipos pendientes.
- Equipos intervenidos.

### Dashboard
- Estado del procedimiento.
- Porcentaje de avance.
- Cronograma.
- Tendencia de cumplimiento.

---

## 3.5 Respaldo de Información
Basado en: **KPIs Respaldo de Información de TICs**

### Funcionalidades
- Programación de backups.
- Registro de ejecución.
- Tipo de respaldo.
- Ubicación de almacenamiento.
- Responsable.
- Validación de restauración.
- Historial de respaldos.

### Indicadores
- % de respaldos ejecutados.
- Respaldos exitosos.
- Respaldos fallidos.
- Cumplimiento del cronograma.

### Dashboard
- Estado del procedimiento.
- Avance general.
- Cumplimiento mensual.
- Tendencias históricas.

---

# 4. Roles del Sistema

## Administrador
- Configuración general.
- Gestión de usuarios.
- Gestión de permisos.
- Acceso total.

## Soporte TIC
- Registro de incidentes.
- Mantenimiento.
- Inventario.
- Respaldos.

## Supervisor TIC
- Revisión y aprobación.
- Seguimiento de KPIs.
- Generación de reportes.

## Usuario Final
- Reporte de incidentes.
- Consulta de estado.

---

# 5. Requisitos Técnicos

## Backend
- API REST.
- Arquitectura modular.
- Auditoría de cambios.
- Registro de actividades (logs).

## Base de Datos
- SQL Server, PostgreSQL o MySQL.
- Integridad referencial.
- Historial de cambios.

## Frontend
- Interfaz web responsiva.
- Dashboard interactivo.
- Exportación a Excel y PDF.

## Seguridad
- Autenticación.
- Roles y permisos.
- Respaldo automático.
- Bitácora de auditoría.

---

# 6. KPIs Mínimos

### Inventario
- Total de activos.
- Activos operativos.
- Activos fuera de servicio.

### Incidentes
- Tiempo promedio de respuesta.
- Tiempo promedio de solución.
- Incidentes por categoría.

### Mantenimiento
- Cumplimiento del plan.
- Equipos atendidos.
- Equipos pendientes.

### Respaldos
- Éxito de respaldos.
- Fallos de respaldo.
- Cumplimiento mensual.

---

# 7. Entregables

1. Base de datos.
2. Aplicación web.
3. Manual de usuario.
4. Manual de administrador.
5. Dashboard de KPIs.
6. Reportes exportables.
7. Bitácora de auditoría.
8. Documentación técnica.

---

# 8. Criterios de Aceptación

- Todos los formularios operativos.
- KPIs calculados automáticamente.
- Dashboard funcional.
- Exportación de reportes.
- Control de accesos por rol.
- Historial de cambios disponible.
- Respaldos registrados y auditables.
