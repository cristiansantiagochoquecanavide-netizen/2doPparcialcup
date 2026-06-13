<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaClase;
use App\Models\AsistenciaDetalle;
use App\Models\Bitacora;
use App\Models\CargaHoraria;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del CU18: Registrar asistencia.
 * Permite registrar asistencia individual de estudiantes segun la carga horaria del docente.
 */
class AsistenciaController extends Controller
{
    public function index()
    {
        return view('gestion-academica.asistencias.index', [
            'asistencias' => AsistenciaClase::with('cargaHoraria.grupo', 'cargaHoraria.materia', 'cargaHoraria.docente')
                ->orderByDesc('fecha_clase')
                ->paginate(15),
            'cargas' => $this->cargasPermitidas()->with('grupo', 'materia', 'docente')->get(),
        ]);
    }

    public function create(Request $request)
    {
        $carga = null;
        $estudiantes = collect();

        if ($request->filled('id_carga_horaria')) {
            $carga = $this->cargasPermitidas()->with('grupo.estudiantesGrupo.inscripcion.postulante', 'materia', 'docente')
                ->findOrFail($request->input('id_carga_horaria'));
            // CU18: muestra estudiantes del grupo asignado a la carga horaria.
            $estudiantes = $carga->grupo->estudiantesGrupo->pluck('inscripcion')->filter();
        }

        return view('gestion-academica.asistencias.create', [
            'cargas' => $this->cargasPermitidas()->with('grupo', 'materia', 'docente')->get(),
            'cargaSeleccionada' => $carga,
            'estudiantes' => $estudiantes,
        ]);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_carga_horaria' => 'required|exists:carga_horaria,id_carga_horaria',
            'fecha_clase' => 'required|date',
            'tema_avanzado' => 'nullable|string|max:200',
            'asistencias' => 'required|array',
            'asistencias.*.estado_asistencia' => 'required|in:PRESENTE,AUSENTE,ATRASO,LICENCIA',
            'asistencias.*.observacion' => 'nullable|string|max:255',
        ]);

        $carga = $this->cargasPermitidas()->with('grupo.estudiantesGrupo.inscripcion')->findOrFail($validado['id_carga_horaria']);

        if (AsistenciaClase::where('id_carga_horaria', $carga->id_carga_horaria)->where('fecha_clase', $validado['fecha_clase'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'Ya existe asistencia registrada para esta clase');
        }

        DB::transaction(function () use ($validado, $carga) {
            // CU18: registra fecha de clase, tema avanzado y usuario que registra.
            $clase = AsistenciaClase::create([
                'id_carga_horaria' => $carga->id_carga_horaria,
                'fecha_clase' => $validado['fecha_clase'],
                'tema_avanzado' => $validado['tema_avanzado'] ?? null,
                'registrado_por' => Auth::id(),
            ]);

            foreach ($carga->grupo->estudiantesGrupo as $asignacion) {
                $idInscripcion = $asignacion->id_inscripcion;
                $detalle = $validado['asistencias'][$idInscripcion] ?? null;

                if ($detalle) {
                    AsistenciaDetalle::create([
                        'id_asistencia_clase' => $clase->id_asistencia_clase,
                        'id_inscripcion' => $idInscripcion,
                        'estado_asistencia' => $detalle['estado_asistencia'],
                        'observacion' => $detalle['observacion'] ?? null,
                    ]);
                }
            }
        });

        $this->registrarBitacora('CREAR', 'Asistencia registrada para carga horaria ID ' . $carga->id_carga_horaria);

        return redirect()->route('asistencias.index')->with('success', 'Asistencia registrada correctamente');
    }

    public function show($id)
    {
        return view('gestion-academica.asistencias.show', [
            'asistencia' => AsistenciaClase::with('cargaHoraria.grupo', 'cargaHoraria.materia', 'asistenciasDetalle.inscripcion.postulante')->findOrFail($id),
        ]);
    }

    private function cargasPermitidas()
    {
        $query = CargaHoraria::query();

        // CU18: si el usuario autenticado corresponde a un docente, solo ve su carga horaria.
        $docente = Docente::where('correo', Auth::user()?->correo)->first();
        if ($docente) {
            $query->where('id_docente', $docente->id_docente);
        }

        return $query;
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Asistencia',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
