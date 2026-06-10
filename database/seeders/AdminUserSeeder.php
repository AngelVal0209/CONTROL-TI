<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@metalarc.com'],
            [
                'name' => 'Administrador',
                'documento' => '73823769',
                'telefono' => '73823769',
                'password' => Hash::make('73823769'),
            ]
        );
        $admin->assignRole('Administrador');

        $soporte = User::updateOrCreate(
            ['email' => 'soporte@metalarc.com'],
            [
                'name' => 'Soporte TIC',
                'documento' => '73823768',
                'telefono' => '73823768',
                'password' => Hash::make('73823768'),
            ]
        );
        $soporte->assignRole('Soporte TIC');

        $consulta = User::updateOrCreate(
            ['email' => 'consulta@metalarc.com'],
            [
                'name' => 'Consulta',
                'documento' => '73823767',
                'telefono' => '73823767',
                'password' => Hash::make('73823767'),
            ]
        );
        $consulta->assignRole('Consulta');
    }
}
