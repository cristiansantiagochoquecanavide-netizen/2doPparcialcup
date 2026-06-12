<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\CupoCarreraGestion;
use App\Models\GestionAcademica;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU10: Gestionar carreras.
 * Permite administrar carreras y configurar cupos por gestion academica.
 */
class CarreraController extends Controller
{
    /**
     * Muestra el listado de carreras registradas.
     * Corresponde al flujo ListarCarreras() del CU10.
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
     * Muestra el formulario de registro de carrera.
     * Corresponde al flujo CrearCarrera() del CU10.
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
     * Registra una nueva carrera.
     * Corresponde al flujo GuardarCarrera() del CU10.
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

            // Registra en bitacora la creacion de una carrera, correspondiente al CU10.
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
     * Muestra estadisticas y cupos asociados a una carrera.
     * Corresponde al flujo ConsultarCarrera() del CU10.
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
     * Muestra el formulario de edicion de carrera.
     * Corresponde al flujo EditarCarrera() del CU10.
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
     * Actualiza codigo, nombre y estado de una carrera.
     * Corresponde al flujo ActualizarCarrera() del CU10.
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

            // Registra en bitacora la actualizacion de una carrera, correspondiente al CU10.
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
     * Elimina una carrera sin postulantes asociados.
     * Corresponde al flujo EliminarCarrera() del CU10.
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

            // Registra en bitacora la eliminacion de una carrera, correspondiente al CU10.
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
     * Muestra el formulario para asignar cupos por gestion academica.
     * Corresponde al flujo AsignarCupoCarrera() del CU10.
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
     * Guarda o actualiza el cupo de una carrera por gestion.
     * Corresponde al flujo GuardarCupoCarrera() del CU10.
     */
    public function guardarCupo(Request $request, $id)
    {
        try {
            // CU11: valida que la carrera exista antes de registrar el cupo por gestion.
            $carrera = Carrera::findOrFail($id);

            // CU11: gestion existente y cupo no negativo; min:1 evita cupos vacios o negativos.
            $validado = $request->validate([
                'id_gestion' => 'required|exists:gestion_academica,id_gestion',
                'cupo_maximo' => 'required|integer|min:1|max:500'
            ]);

            // CU11: evita duplicidad de cupo para la misma carrera y gestion.
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

            // Registra en bitacora la asignacion o actualizacion de cupo, correspondiente al CU10.
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
