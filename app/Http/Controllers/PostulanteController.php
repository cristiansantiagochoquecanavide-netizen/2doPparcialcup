<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Carrera;
use App\Models\Requisito;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostulanteController extends Controller
{
    /**
     * Listar todos los postulantes
     */
    public function index()
    {
        try {
            $postulantes = Postulante::with('carreraOpcionPrimera', 'carreraOpcionSegunda')
                ->paginate(15);
            
            return view('postulantes.index', [
                'postulantes' => $postulantes
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar postulantes: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear postulante
     */
    public function create()
    {
        try {
            $carreras = Carrera::where('estado', 'ACTIVA')->get();
            
            return view('postulantes.create', [
                'carreras' => $carreras
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo postulante
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate([
                'ci' => 'required|string|unique:postulantes,ci|max:20',
                'nombres' => 'required|string|max:80',
                'apellidos' => 'required|string|max:80',
                'fecha_nacimiento' => 'required|date|before:today',
                'sexo' => 'required|in:M,F',
                'direccion' => 'nullable|string|max:150',
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|email|unique:postulantes,correo|max:100',
                'colegio_procedencia' => 'nullable|string|max:120',
                'ciudad' => 'nullable|string|max:80',
                'titulo_bachiller' => 'nullable|string|max:120',
                'otros_requisitos' => 'nullable|string|max:255',
                'id_carrera_primera_opcion' => 'required|exists:carreras,id_carrera',
                'id_carrera_segunda_opcion' => 'nullable|exists:carreras,id_carrera',
                'estado' => 'required|in:REGISTRADO,VALIDADO,INSCRITO,RECHAZADO'
            ]);

            $postulante = Postulante::create($validado);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'CREAR',
                'descripcion' => 'Postulante ' . $postulante->nombres . ' ' . $postulante->apellidos . ' registrado'
            ]);

            return redirect()->route('postulantes.show', $postulante->id_postulante)
                ->with('success', 'Postulante registrado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar postulante: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un postulante
     */
    public function show($id)
    {
        try {
            $postulante = Postulante::with(
                'carreraOpcionPrimera',
                'carreraOpcionSegunda',
                'requisitos',
                'pagos',
                'inscripciones'
            )->findOrFail($id);
            
            return view('postulantes.show', [
                'postulante' => $postulante
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Postulante no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar postulante
     */
    public function edit($id)
    {
        try {
            $postulante = Postulante::findOrFail($id);
            $carreras = Carrera::where('estado', 'ACTIVA')->get();
            
            return view('postulantes.edit', [
                'postulante' => $postulante,
                'carreras' => $carreras
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Postulante no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar postulante
     */
    public function update(Request $request, $id)
    {
        try {
            $postulante = Postulante::findOrFail($id);

            $validado = $request->validate([
                'ci' => 'required|string|unique:postulantes,ci,' . $id . ',id_postulante|max:20',
                'nombres' => 'required|string|max:80',
                'apellidos' => 'required|string|max:80',
                'fecha_nacimiento' => 'required|date|before:today',
                'sexo' => 'required|in:M,F',
                'direccion' => 'nullable|string|max:150',
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|email|unique:postulantes,correo,' . $id . ',id_postulante|max:100',
                'colegio_procedencia' => 'nullable|string|max:120',
                'ciudad' => 'nullable|string|max:80',
                'titulo_bachiller' => 'nullable|string|max:120',
                'otros_requisitos' => 'nullable|string|max:255',
                'id_carrera_primera_opcion' => 'required|exists:carreras,id_carrera',
                'id_carrera_segunda_opcion' => 'nullable|exists:carreras,id_carrera',
                'estado' => 'required|in:REGISTRADO,VALIDADO,INSCRITO,RECHAZADO'
            ]);

            $postulante->update($validado);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'ACTUALIZAR',
                'descripcion' => 'Postulante ' . $postulante->nombres . ' ' . $postulante->apellidos . ' actualizado'
            ]);

            return redirect()->route('postulantes.show', $id)
                ->with('success', 'Postulante actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar postulante: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar postulante
     */
    public function destroy($id)
    {
        try {
            $postulante = Postulante::findOrFail($id);
            $nombrePostulante = $postulante->nombres . ' ' . $postulante->apellidos;

            // Verificar si tiene inscripciones
            if ($postulante->inscripciones()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el postulante porque tiene inscripciones');
            }

            // Registrar en bitácora antes de eliminar
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'ELIMINAR',
                'descripcion' => 'Postulante ' . $nombrePostulante . ' eliminado del sistema'
            ]);

            $postulante->delete();

            return redirect()->route('postulantes.index')
                ->with('success', 'Postulante eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar postulante: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del postulante
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $postulante = Postulante::findOrFail($id);

            $validado = $request->validate([
                'estado' => 'required|in:REGISTRADO,VALIDADO,INSCRITO,RECHAZADO'
            ]);

            $estadoAnterior = $postulante->estado;
            $postulante->update(['estado' => $validado['estado']]);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'CAMBIAR ESTADO',
                'descripcion' => 'Postulante ' . $postulante->nombres . ' cambió de estado: ' . $estadoAnterior . ' → ' . $validado['estado']
            ]);

            return redirect()->back()
                ->with('success', 'Estado del postulante actualizado');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }
}
