<?php

namespace App\Http\Controllers\Usuario;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\Usuario\UsuarioService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function __construct(
        protected UsuarioService $usuarioService
    ) {}

    public function index()
    {
        return Inertia::render('Usuarios/Index', [
            'usuarios' => User::with('roles')->latest()->paginate(10),
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Usuarios/Form', [
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $this->usuarioService->validateAndCreate($request);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $usuario->load('roles');
        return Inertia::render('Usuarios/Form', [
            'usuario' => $usuario,
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $this->usuarioService->validateAndUpdate($request, $usuario);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $this->usuarioService->delete($usuario);
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function assignRole(Request $request, User $usuario)
    {
        $this->usuarioService->assignRole($request, $usuario);
        return back()->with('success', 'Rol asignado correctamente.');
    }

    public function removeRole(Request $request, User $usuario)
    {
        $this->usuarioService->removeRole($request, $usuario);
        return back()->with('success', 'Rol removido correctamente.');
    }
}



