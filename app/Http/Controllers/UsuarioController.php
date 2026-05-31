<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        try {
            $usuarios = Usuario::with('rol')->paginate(15);
            
            return view('usuarios.index', [
                'usuarios' => $usuarios
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear usuario
     */
    public function create()
    {
        try {
            $roles = Rol::where('estado', 'ACTIVO')->get();
            
            return view('usuarios.create', [
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate([
                'nombre_usuario' => 'required|string|unique:usuarios,nombre_usuario|max:50',
                'correo' => 'required|email|unique:usuarios,correo|max:100',
                'password' => 'required|string|min:8|confirmed',
                'id_rol' => 'required|exists:roles,id_rol',
                'estado' => 'required|in:ACTIVO,INACTIVO'
            ]);

            $usuario = Usuario::create([
                'nombre_usuario' => $validado['nombre_usuario'],
                'correo' => $validado['correo'],
                'password_hash' => Hash::make($validado['password']),
                'id_rol' => $validado['id_rol'],
                'estado' => $validado['estado']
            ]);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Usuarios',
                'accion' => 'CREAR',
                'descripcion' => 'Usuario ' . $usuario->nombre_usuario . ' creado exitosamente'
            ]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un usuario
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::with('rol', 'bitacoras')->findOrFail($id);
            
            return view('usuarios.show', [
                'usuario' => $usuario
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function edit($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $roles = Rol::where('estado', 'ACTIVO')->get();
            
            return view('usuarios.edit', [
                'usuario' => $usuario,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $validado = $request->validate([
                'nombre_usuario' => 'required|string|unique:usuarios,nombre_usuario,' . $id . ',id_usuario|max:50',
                'correo' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario|max:100',
                'id_rol' => 'required|exists:roles,id_rol',
                'estado' => 'required|in:ACTIVO,INACTIVO',
                'password' => 'nullable|string|min:8|confirmed'
            ]);

            $datosActualizar = [
                'nombre_usuario' => $validado['nombre_usuario'],
                'correo' => $validado['correo'],
                'id_rol' => $validado['id_rol'],
                'estado' => $validado['estado']
            ];

            if (!empty($validado['password'])) {
                $datosActualizar['password_hash'] = Hash::make($validado['password']);
            }

            $usuario->update($datosActualizar);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Usuarios',
                'accion' => 'ACTUALIZAR',
                'descripcion' => 'Usuario ' . $usuario->nombre_usuario . ' actualizado'
            ]);

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $nombreUsuario = $usuario->nombre_usuario;

            // Registrar en bitácora antes de eliminar
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Usuarios',
                'accion' => 'ELIMINAR',
                'descripcion' => 'Usuario ' . $nombreUsuario . ' eliminado del sistema'
            ]);

            $usuario->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }
}
