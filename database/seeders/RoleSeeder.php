<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = ['equipos', 'incidentes', 'configuraciones', 'mantenimientos', 'respaldos', 'auditoria'];

        $acciones = ['listar', 'crear', 'editar', 'eliminar'];

        foreach ($modulos as $modulo) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate(['name' => "$modulo.$accion"]);
            }
        }

        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $soporte = Role::firstOrCreate(['name' => 'Soporte TIC']);
        $consulta = Role::firstOrCreate(['name' => 'Consulta']);

        $admin->syncPermissions(Permission::all());

        $soporte->syncPermissions(
            Permission::whereIn('name', [
                'equipos.listar', 'equipos.crear', 'equipos.editar', 'equipos.eliminar',
                'incidentes.listar', 'incidentes.crear', 'incidentes.editar', 'incidentes.eliminar',
                'configuraciones.listar', 'configuraciones.crear', 'configuraciones.editar', 'configuraciones.eliminar',
                'mantenimientos.listar', 'mantenimientos.crear', 'mantenimientos.editar', 'mantenimientos.eliminar',
                'respaldos.listar', 'respaldos.crear', 'respaldos.editar', 'respaldos.eliminar',
            ])->get()
        );

        $consulta->syncPermissions(
            Permission::whereIn('name', [
                'equipos.listar',
                'incidentes.listar',
                'configuraciones.listar',
                'mantenimientos.listar',
                'respaldos.listar',
                'auditoria.listar',
            ])->get()
        );
    }
}
