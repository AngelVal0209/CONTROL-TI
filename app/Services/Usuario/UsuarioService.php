<?php

namespace App\Services\Usuario;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsuarioService
{
    public function indexData()
    {
        return Inertia::render('Usuarios/Index', [
            'usuarios' => User::with('roles')->latest()->paginate(10),
            'roles' => Role::all(),
        ]);
    }

    public function createData()
    {
        return Inertia::render('Usuarios/Form', [
            'roles' => Role::all(),
        ]);
    }

    public function editData(User $usuario)
    {
        $usuario->load('roles');
        return Inertia::render('Usuarios/Form', [
            'usuario' => $usuario,
            'roles' => Role::all(),
        ]);
    }

    public function validateAndCreate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'documento' => 'nullable|string|unique:users,documento',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string',
            'password' => 'required|string|min:4',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        if (!empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        AuditoriaService::registrar('crear', 'usuarios', $user->id, "Usuario {$user->name} creado");
        return $user;
    }

    public function validateAndUpdate(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'documento' => 'nullable|string|unique:users,documento,' . $usuario->id,
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'telefono' => 'nullable|string',
            'password' => 'nullable|string|min:4',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $usuario->update($validated);

        if (isset($validated['roles'])) {
            $usuario->syncRoles($validated['roles']);
        }

        AuditoriaService::registrar('actualizar', 'usuarios', $usuario->id, "Usuario {$usuario->name} actualizado");
        return $usuario;
    }

    public function delete(User $usuario)
    {
        $usuario->delete();
        AuditoriaService::registrar('eliminar', 'usuarios', $usuario->id, "Usuario {$usuario->name} eliminado");
    }

    public function assignRole(Request $request, User $usuario)
    {
        $validated = $request->validate(['role' => 'required|exists:roles,id']);
        $role = Role::findById($validated['role']);
        $usuario->assignRole($role);
        AuditoriaService::registrar('asignar_rol', 'usuarios', $usuario->id, "Rol {$role->name} asignado a {$usuario->name}");
    }

    public function removeRole(Request $request, User $usuario)
    {
        $validated = $request->validate(['role' => 'required|exists:roles,id']);
        $role = Role::findById($validated['role']);
        $usuario->removeRole($role);
        AuditoriaService::registrar('remover_rol', 'usuarios', $usuario->id, "Rol {$role->name} removido de {$usuario->name}");
    }
}


