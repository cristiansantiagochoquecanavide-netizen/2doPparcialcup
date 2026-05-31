<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CupoCarreraGestion;
use App\Models\GestionAcademica;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarreraController extends Controller
{
    /**
     * Listar todas las carreras
     */
    public function index()
    {
        try {
            $carreras = Carrera::withCount('postulantesOpcionPrimera', 'postulantesOpcionSegunda')
                ->paginate(15);
            
            return view('carreras.index', [
                'carreras' => $carreras
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar carreras: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear carrera
     */
    public function create()
    {
        try {
            return view('carreras.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nueva carrera
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate([
                'codigo' => 'required|string|unique:carreras,codigo|max:20',
                'nombre' => 'required|string|unique:carreras,nombre|max:100',
                'estado' => 'required|in:ACTIVA,INACTIVA'
            ]);

            $carrera = Carrera::create($validado);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Carreras',
                'accion' => 'CREAR',
                'descripcion' => 'Carrera ' . $carrera->nombre . ' creada exitosamente'
            ]);

            return redirect()->route('carreras.index')
                ->with('success', 'Carrera creada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear carrera: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una carrera
     */
    public function show($id)
    {
        try {
            $carrera = Carrera::with(
                'cuposGestiones.gestion',
                'postulantesOpcionPrimera',
                'postulantesOpcionSegunda'
            )->findOrFail($id);
            
            $estadisticas = [
                'postulantes_opcion_1' => $carrera->postulantesOpcionPrimera()->count(),
                'postulantes_opcion_2' => $carrera->postulantesOpcionSegunda()->count(),
                'cupos_asignados' => $carrera->cuposGestiones()->count()
            ];

            return view('carreras.show', [
                'carrera' => $carrera,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Carrera no encontrada: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar carrera
     */
    public function edit($id)
    {
        try {
            $carrera = Carrera::findOrFail($id);
            
            return view('carreras.edit', [
                'carrera' => $carrera
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Carrera no encontrada: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar carrera
     */
    public function update(Request $request, $id)
    {
        try {
            $carrera = Carrera::findOrFail($id);

            $validado = $request->validate([
                'codigo' => 'required|string|unique:carreras,codigo,' . $id . ',id_carrera|max:20',
                'nombre' => 'required|string|unique:carreras,nombre,' . $id . ',id_carrera|max:100',
                'estado' => 'required|in:ACTIVA,INACTIVA'
            ]);

            $carrera->update($validado);

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Carreras',
                'accion' => 'ACTUALIZAR',
                'descripcion' => 'Carrera ' . $carrera->nombre . ' actualizada'
            ]);

            return redirect()->route('carreras.index')
                ->with('success', 'Carrera actualizada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar carrera: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar carrera
     */
    public function destroy($id)
    {
        try {
            $carrera = Carrera::findOrFail($id);
            $nombreCarrera = $carrera->nombre;

            // Verificar si tiene postulantes asociados
            if ($carrera->postulantesOpcionPrimera()->count() > 0 || 
                $carrera->postulantesOpcionSegunda()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar la carrera porque tiene postulantes asociados');
            }

            // Registrar en bitácora antes de eliminar
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Carreras',
                'accion' => 'ELIMINAR',
                'descripcion' => 'Carrera ' . $nombreCarrera . ' eliminada del sistema'
            ]);

            $carrera->delete();

            return redirect()->route('carreras.index')
                ->with('success', 'Carrera eliminada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar carrera: ' . $e->getMessage());
        }
    }

    /**
     * Asignar cupos a una carrera en una gestión específica
     */
    public function asignarCupo($id)
    {
        try {
            $carrera = Carrera::findOrFail($id);
            $gestiones = GestionAcademica::where('estado', 'ACTIVA')->get();
            $cuposActuales = CupoCarreraGestion::where('id_carrera', $id)->get();

            return view('carreras.asignar-cupo', [
                'carrera' => $carrera,
                'gestiones' => $gestiones,
                'cuposActuales' => $cuposActuales
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar cupos: ' . $e->getMessage());
        }
    }

    /**
     * Guardar cupo asignado a una carrera
     */
    public function guardarCupo(Request $request, $id)
    {
        try {
            $carrera = Carrera::findOrFail($id);

            $validado = $request->validate([
                'id_gestion' => 'required|exists:gestion_academica,id_gestion',
                'cupo_maximo' => 'required|integer|min:1|max:500'
            ]);

            // Verificar si ya existe el cupo
            $cupoExistente = CupoCarreraGestion::where('id_carrera', $id)
                ->where('id_gestion', $validado['id_gestion'])
                ->first();

            if ($cupoExistente) {
                $cupoExistente->update(['cupo_maximo' => $validado['cupo_maximo']]);
                $accion = 'ACTUALIZAR CUPO';
            } else {
                CupoCarreraGestion::create([
                    'id_carrera' => $id,
                    'id_gestion' => $validado['id_gestion'],
                    'cupo_maximo' => $validado['cupo_maximo']
                ]);
                $accion = 'ASIGNAR CUPO';
            }

            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Carreras',
                'accion' => $accion,
                'descripcion' => 'Cupo de ' . $validado['cupo_maximo'] . ' asignado a ' . $carrera->nombre
            ]);

            return redirect()->route('carreras.show', $id)
                ->with('success', 'Cupo asignado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al asignar cupo: ' . $e->getMessage());
        }
    }
}
