# 🖥️ TI Control - Sistema de Gestión de Activos TI

Sistema web para la gestión integral de activos informáticos, desarrollado con **Laravel 12** + **Inertia.js** + **Vue 3** + **PrimeVue**.

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
- **Arquitectura N-Capas**: Repositorios + Servicios para escalabilidad

## 🚀 Requisitos

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
