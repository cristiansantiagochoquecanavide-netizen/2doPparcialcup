<?php

namespace App\Http\Controllers\SeguridadUsuarios;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU4: Gestionar roles y asignar permisos.
 * Administra roles, sus permisos asociados y restricciones de eliminacion.
 */
class RolController extends Controller
{
    /**
     * Muestra el listado de roles registrados.
     * Corresponde al flujo ListarRoles() del CU4.
     */
    public function index()
    {
        try {
            $roles = Rol::with('permisos')->paginate(15);
            
            return view('seguridad-usuarios.roles.index', [
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar roles: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para registrar un rol.
     * Corresponde al flujo CrearRol() del CU4.
     */
    public function create()
    {
        try {
            return view('seguridad-usuarios.roles.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Registra un nuevo rol del sistema.
     * Corresponde al flujo GuardarRol() del CU4.
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate([
                'nombre' => 'required|string|unique:roles,nombre|max:50',
                'descripcion' => 'nullable|string|max:150',
                'estado' => 'required|in:ACTIVO,INACTIVO'
            ]);

            $rol = Rol::create($validado);

            // Registra en bitacora la creacion de un rol, correspondiente al CU4.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Roles',
                'accion' => 'CREAR',
                'descripcion' => 'Rol ' . $rol->nombre . ' creado exitosamente'
            ]);

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear rol: ' . $e->getMessage());
        }
    }

    /**
     * Muestra datos, permisos y usuarios asociados a un rol.
     * Corresponde al flujo ConsultarRol() del CU4.
     */
    public function show($id)
    {
        try {
            $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
            
            return view('seguridad-usuarios.roles.show', [
                'rol' => $rol
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Rol no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edicion de rol.
     * Corresponde al flujo EditarRol() del CU4.
     */
    public function edit($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            
            return view('seguridad-usuarios.roles.edit', [
                'rol' => $rol
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Rol no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza nombre, descripcion y estado de un rol.
     * Corresponde al flujo ActualizarRol() del CU4.
     */
    public function update(Request $request, $id)
    {
        try {
            $rol = Rol::findOrFail($id);

            $validado = $request->validate([
                'nombre' => 'required|string|unique:roles,nombre,' . $id . ',id_rol|max:50',
                'descripcion' => 'nullable|string|max:150',
                'estado' => 'required|in:ACTIVO,INACTIVO'
            ]);

            $rol->update($validado);

            // Registra en bitacora la actualizacion de un rol, correspondiente al CU4.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Roles',
                'accion' => 'ACTUALIZAR',
                'descripcion' => 'Rol ' . $rol->nombre . ' actualizado'
            ]);

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar rol: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un rol sin usuarios asociados.
     * Corresponde al flujo EliminarRol() del CU4.
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $nombreRol = $rol->nombre;

            // Verificar si tiene usuarios asociados
            if ($rol->usuarios()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el rol porque tiene usuarios asociados');
            }

            // Registra en bitacora la eliminacion de un rol, correspondiente al CU4.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Roles',
                'accion' => 'ELIMINAR',
                'descripcion' => 'Rol ' . $nombreRol . ' eliminado del sistema'
            ]);

            $rol->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar rol: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los permisos disponibles para asignarlos al rol.
     * Corresponde al flujo AsignarPermisos() del CU4.
     */
    public function asignarPermisos($id)
    {
        try {
            $rol = Rol::with('permisos')->findOrFail($id);
            $todosPermisos = Permiso::all();
            $permisosAsignados = $rol->permisos->pluck('id_permiso')->toArray();

            return view('seguridad-usuarios.roles.asignar-permisos', [
                'rol' => $rol,
                'todosPermisos' => $todosPermisos,
                'permisosAsignados' => $permisosAsignados
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar permisos: ' . $e->getMessage());
        }
    }

    /**
     * Sincroniza los permisos asignados a un rol.
     * Corresponde al flujo GuardarPermisos() del CU4.
     */
    public function guardarPermisos(Request $request, $id)
    {
        try {
            $rol = Rol::findOrFail($id);

            $validado = $request->validate([
                'permisos' => 'array',
                'permisos.*' => 'exists:permisos,id_permiso'
            ]);

            // Sincronizar permisos (detach y attach)
            $rol->permisos()->sync($validado['permisos'] ?? []);

            // Registra en bitacora la asignacion de permisos, correspondiente al CU4.
            $cantidadPermisos = count($validado['permisos'] ?? []);
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Roles',
                'accion' => 'ASIGNAR PERMISOS',
                'descripcion' => 'Se asignaron ' . $cantidadPermisos . ' permisos al rol ' . $rol->nombre
            ]);

            return redirect()->route('roles.show', $id)
                ->with('success', 'Permisos asignados exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al asignar permisos: ' . $e->getMessage());
        }
    }
}
