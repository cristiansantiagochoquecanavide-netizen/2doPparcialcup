<?php

namespace App\Http\Controllers\SeguridadUsuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU3: Gestionar usuarios.
 * Permite listar, registrar, modificar, consultar y eliminar usuarios del sistema.
 */
class UsuarioController extends Controller
{
    /**
     * Muestra el listado de usuarios registrados.
     * Corresponde al flujo ListarUsuarios() del CU3.
     */
    public function index()
    {
        try {
            $usuarios = Usuario::with('rol')->paginate(15);
            
            return view('seguridad-usuarios.usuarios.index', [
                'usuarios' => $usuarios
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de registro de usuario.
     * Corresponde al flujo CrearUsuario() del CU3.
     */
    public function create()
    {
        try {
            $roles = Rol::where('estado', 'ACTIVO')->get();
            
            return view('seguridad-usuarios.usuarios.create', [
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Registra un nuevo usuario con rol y credenciales.
     * Corresponde al flujo GuardarUsuario() del CU3.
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

            // Registra en bitacora la creacion de un usuario, correspondiente al CU3.
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
     * Muestra los datos detallados de un usuario.
     * Corresponde al flujo ConsultarUsuario() del CU3.
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::with('rol', 'bitacoras')->findOrFail($id);
            
            return view('seguridad-usuarios.usuarios.show', [
                'usuario' => $usuario
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edicion de usuario.
     * Corresponde al flujo EditarUsuario() del CU3.
     */
    public function edit($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $roles = Rol::where('estado', 'ACTIVO')->get();
            
            return view('seguridad-usuarios.usuarios.edit', [
                'usuario' => $usuario,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Usuario no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza datos, rol, estado y credenciales opcionales del usuario.
     * Corresponde al flujo ActualizarUsuario() del CU3.
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

            // Registra en bitacora la actualizacion de un usuario, correspondiente al CU3.
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
     * Elimina un usuario registrado del sistema.
     * Corresponde al flujo EliminarUsuario() del CU3.
     */
    public function destroy($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $nombreUsuario = $usuario->nombre_usuario;

            // Registra en bitacora la eliminacion de un usuario, correspondiente al CU3.
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
