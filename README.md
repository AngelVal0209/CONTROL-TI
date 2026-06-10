# 🖥️ TI Control - Sistema de Gestión de Activos TI

Sistema de escritorio/portable para la gestión integral de activos informáticos, desarrollado con **Laravel 12** + **Inertia.js** + **Vue 3** + **PrimeVue**.

## ✨ Características

- **Equipos**: CRUD completo con registro por usuario, áreas y puestos de trabajo
- **Incidentes**: Gestión de incidencias con prioridades, estados y resolución
- **Configuraciones**: Registro de software/hardware por equipo con historial de cambios
- **Mantenimientos**: Programación y seguimiento de mantenimientos preventivos/correctivos
- **Respaldos**: Control de backups con tipos y ubicaciones
- **KPIs**: Dashboard con indicadores clave de inventario, incidentes, mantenimiento y respaldos
- **Reportes**: Exportación a PDF y Excel
- **Auditoría**: Trazabilidad completa de todas las acciones del sistema
- **Roles**: Administrador, Soporte TIC y Consulta
- **Renovaciones**: Control de licencias, suscripciones y vencimientos
- **Backup/Restore**: Exportación e importación completa de datos y archivos (tipos, marcas, modelos, equipos, incidentes, configuraciones, mantenimientos, respaldos, renovaciones, usuarios, áreas, puestos, auditoría)
- **Quick-create**: Catálogos con creación rápida (tipos, marcas, modelos, áreas, puestos)
- **Notificaciones**: Alertas de renovaciones próximas a vencer
- **Arquitectura N-Capas**: Repositorios + Servicios para escalabilidad

## 📦 Versión Portable (Desktop)

Listo para ejecutar en Windows sin instalar PHP, Composer ni Node.js.

### Requisitos
- Windows 10/11 (64 bits)
- Ningún otro requisito técnico

### Instalación
1. Descargar `TI-Control-Portable.zip` desde la sección de releases
2. Extraer en cualquier carpeta (ej. `C:\TI Control`)
3. Ejecutar `Instalar.bat` (descarga PHP automáticamente)
4. Ejecutar `Iniciar.bat` para usar el sistema
5. Usuario: `73823769` / Contraseña: `73823769`

### Crear nuevo paquete portable
```bash
powershell -ExecutionPolicy Bypass -File build.ps1
```
Genera la carpeta `dist/` con PHP portable + app optimizada + instalador.

## 🚀 Requisitos (Desarrollo)

- PHP 8.2+
- Composer 2+
- Node.js 20+
- SQLite (por defecto) / MySQL / PostgreSQL

## ⚙️ Instalación

```bash
# Clonar repositorio
git clone https://github.com/AngelVal0209/CONTROL-TI.git
cd CONTROL-TI

# Instalar dependencias PHP
composer install

# Instalar dependencias JS
npm install

# Copiar configuración
cp .env.example .env

# Generar key
php artisan key:generate

# Migrar base de datos
php artisan migrate --force

# Poblar datos iniciales
php artisan db:seed --force

# Assets para producción
npm run build
```

## 🏃 Ejecutar (Desarrollo)

**Terminal 1** - Servidor de assets Vite:
```bash
npm run dev
```

**Terminal 2** - Servidor Laravel:
```bash
php artisan serve
```

Acceder a **http://localhost:8000**

## 🔐 Usuarios por defecto

| Documento | Contraseña | Rol |
|-----------|-----------|-----|
| 73823769 | 73823769 | Administrador |
| 73823768 | 73823768 | Soporte TIC |
| 73823767 | 73823767 | Consulta |

## 🏗️ Arquitectura

```
app/
├── Http/Controllers/   ← Controladores delgados
├── Repositories/       ← Capa de acceso a datos
├── Services/           ← Capa de lógica de negocio
└── Models/             ← Modelos Eloquent
```

## 🛠️ Tecnologías

- **Backend**: Laravel 12, Spatie Permission, DomPDF, Laravel Excel
- **Frontend**: Vue 3, Inertia.js, PrimeVue 4, Tailwind CSS 4, Pinia
- **Base de datos**: SQLite (portable, sin servidor)
- **Empaquetado**: Vite 7, Tauri (para desktop)
