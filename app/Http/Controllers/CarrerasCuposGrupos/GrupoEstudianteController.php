<?php

namespace App\Http\Controllers\CarrerasCuposGrupos;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Grupo;
use App\Models\GrupoEstudiante;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU13: Asignar estudiantes a grupos.
 * Valida inscripcion, grupo activo, cupo disponible y evita doble asignacion.
 */
class GrupoEstudianteController extends Controller
{
    public function index()
    {
        return view('carreras-cupos-grupos.asignaciones.index', [
            'asignaciones' => GrupoEstudiante::with('grupo.gestion', 'inscripcion.postulante')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('carreras-cupos-grupos.asignaciones.create', [
            'grupos' => Grupo::withCount('estudiantesGrupo')->where('estado', 'ACTIVO')->get(),
            'inscripciones' => Inscripcion::with('postulante', 'gestion')
                ->whereDoesntHave('grupoEstudiante')
                ->orderByDesc('id_inscripcion')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_grupo' => 'required|exists:grupos,id_grupo',
            'id_inscripcion' => 'required|exists:inscripciones,id_inscripcion|unique:grupo_estudiante,id_inscripcion',
        ]);

        $grupo = Grupo::withCount('estudiantesGrupo')->findOrFail($validado['id_grupo']);
        $inscripcion = Inscripcion::findOrFail($validado['id_inscripcion']);

        // CU13: el grupo debe estar activo y con cupo disponible.
        if ($grupo->estado !== 'ACTIVO') {
            return redirect()->back()->withInput()->with('error', 'El grupo no esta activo');
        }

        if ($grupo->estudiantes_grupo_count >= $grupo->cupo_maximo) {
            return redirect()->back()->withInput()->with('error', 'El grupo no tiene cupo disponible');
        }

        // CU13: evita asignar estudiantes a grupos de otra gestion.
        if ((int) $grupo->id_gestion !== (int) $inscripcion->id_gestion) {
            return redirect()->back()->withInput()->with('error', 'La inscripcion y el grupo no pertenecen a la misma gestion');
        }

        $asignacion = GrupoEstudiante::create($validado);

        $this->registrarBitacora('CREAR', 'Inscripcion ID ' . $asignacion->id_inscripcion . ' asignada al grupo ID ' . $asignacion->id_grupo);

        return redirect()->route('grupos.show', $grupo->id_grupo)->with('success', 'Estudiante asignado correctamente');
    }

    public function destroy($id)
    {
        $asignacion = GrupoEstudiante::findOrFail($id);
        $idGrupo = $asignacion->id_grupo;
        $asignacion->delete();

        $this->registrarBitacora('ELIMINAR', 'Asignacion de grupo ID ' . $id . ' eliminada');

        return redirect()->route('grupos.show', $idGrupo)->with('success', 'Asignacion eliminada correctamente');
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Asignacion de Estudiantes a Grupos',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
