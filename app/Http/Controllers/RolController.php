<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Permiso;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    /**
     * Listar todos los roles
     */
    public function index()
    {
        try {
            $roles = Rol::with('permisos')->paginate(15);
            
            return view('roles.index', [
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar roles: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear rol
     */
    public function create()
    {
        try {
            return view('roles.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo rol
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

            // Registrar en bitácora
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
     * Mostrar detalles de un rol
     */
    public function show($id)
    {
        try {
            $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
            
            return view('roles.show', [
                'rol' => $rol
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Rol no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar rol
     */
    public function edit($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            
            return view('roles.edit', [
                'rol' => $rol
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Rol no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar rol
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

            // Registrar en bitácora
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
     * Eliminar rol
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

            // Registrar en bitácora antes de eliminar
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
     * Mostrar formulario para asignar permisos a un rol
     */
    public function asignarPermisos($id)
    {
        try {
            $rol = Rol::with('permisos')->findOrFail($id);
            $todosPermisos = Permiso::all();
            $permisosAsignados = $rol->permisos->pluck('id_permiso')->toArray();

            return view('roles.asignar-permisos', [
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
     * Guardar permisos asignados a un rol
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

            // Registrar en bitácora
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
