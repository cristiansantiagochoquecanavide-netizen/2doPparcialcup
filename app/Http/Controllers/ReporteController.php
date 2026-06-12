<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Carrera;
use App\Models\CargaHoraria;
use App\Models\GestionAcademica;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Postulante;
use App\Models\ResultadoAdmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del CU23: Generar reportes.
 * Consolida reportes obligatorios con filtros por gestion, carrera, grupo, materia, estado y fechas.
 */
class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->only([
            'id_gestion',
            'id_carrera',
            'id_grupo',
            'id_materia',
            'estado',
            'fecha_inicio',
            'fecha_fin',
        ]);

        // CU23: lista general de postulantes filtrable por estado, carrera, gestion, grupo y rango de fechas.
        $postulantes = Postulante::with('carreraOpcionPrimera', 'carreraOpcionSegunda', 'inscripciones.gestion')
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->estado))
            ->when($request->filled('id_carrera'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('id_carrera_primera_opcion', $request->id_carrera)
                        ->orWhere('id_carrera_segunda_opcion', $request->id_carrera);
                });
            })
            ->when($request->filled('fecha_inicio'), fn ($query) => $query->whereDate('fecha_registro', '>=', $request->fecha_inicio))
            ->when($request->filled('fecha_fin'), fn ($query) => $query->whereDate('fecha_registro', '<=', $request->fecha_fin))
            ->when($request->filled('id_gestion'), function ($query) use ($request) {
                $query->whereHas('inscripciones', fn ($query) => $query->where('id_gestion', $request->id_gestion));
            })
            ->when($request->filled('id_grupo'), function ($query) use ($request) {
                $query->whereHas('inscripciones.grupoEstudiante', fn ($query) => $query->where('id_grupo', $request->id_grupo));
            })
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();

        $resultados = ResultadoAdmision::with('inscripcion.postulante', 'carreraAdmitida')
            ->when($request->filled('id_gestion'), fn ($query) => $query->whereHas('inscripcion', fn ($q) => $q->where('id_gestion', $request->id_gestion)))
            ->when($request->filled('id_carrera'), fn ($query) => $query->where('id_carrera_admitida', $request->id_carrera))
            ->get();

        // CU23: postulantes aprobados, reprobados y promedios generales.
        $aprobados = $resultados->where('estado_resultado', 'APROBADO');
        $reprobados = $resultados->where('estado_resultado', 'REPROBADO');
        $promedioGeneral = round((float) $resultados->avg('promedio_final'), 2);

        // CU23: cantidad de grupos habilitados.
        $gruposHabilitados = Grupo::withCount('estudiantesGrupo')
            ->where('estado', 'ACTIVO')
            ->when($request->filled('id_gestion'), fn ($query) => $query->where('id_gestion', $request->id_gestion))
            ->when($request->filled('id_grupo'), fn ($query) => $query->where('id_grupo', $request->id_grupo))
            ->get();

        // CU23: estadisticas por materia usando notas registradas por evaluacion.
        $estadisticasMateria = Materia::query()
            ->leftJoin('evaluacion_config', 'materias.id_materia', '=', 'evaluacion_config.id_materia')
            ->leftJoin('notas', 'evaluacion_config.id_evaluacion', '=', 'notas.id_evaluacion')
            ->when($request->filled('id_materia'), fn ($query) => $query->where('materias.id_materia', $request->id_materia))
            ->select('materias.nombre', DB::raw('COUNT(notas.id_nota) as total_notas'), DB::raw('AVG(notas.nota) as promedio'))
            ->groupBy('materias.id_materia', 'materias.nombre')
            ->orderBy('materias.nombre')
            ->get();

        // CU23: docentes por grupo desde carga_horaria.
        $docentesPorGrupo = CargaHoraria::with('grupo', 'materia', 'docente', 'aula')
            ->when($request->filled('id_grupo'), fn ($query) => $query->where('id_grupo', $request->id_grupo))
            ->when($request->filled('id_materia'), fn ($query) => $query->where('id_materia', $request->id_materia))
            ->get();

        // CU23: grupos con mayor cantidad de aprobados.
        $gruposConAprobados = Grupo::query()
            ->leftJoin('grupo_estudiante', 'grupos.id_grupo', '=', 'grupo_estudiante.id_grupo')
            ->leftJoin('inscripciones', 'grupo_estudiante.id_inscripcion', '=', 'inscripciones.id_inscripcion')
            ->leftJoin('resultado_admision', 'inscripciones.id_inscripcion', '=', 'resultado_admision.id_inscripcion')
            ->when($request->filled('id_gestion'), fn ($query) => $query->where('grupos.id_gestion', $request->id_gestion))
            ->select('grupos.codigo_grupo', DB::raw("SUM(CASE WHEN resultado_admision.estado_resultado = 'APROBADO' THEN 1 ELSE 0 END) as aprobados"))
            ->groupBy('grupos.id_grupo', 'grupos.codigo_grupo')
            ->orderByDesc('aprobados')
            ->limit(10)
            ->get();

        // CU23: registra en bitacora cada generacion de reportes.
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Reportes',
            'accion' => 'GENERAR',
            'descripcion' => 'Consulta de reportes administrativos'
        ]);

        return view('reportes.index', [
            'filtros' => $filtros,
            'gestiones' => GestionAcademica::orderByDesc('anio')->get(),
            'carreras' => Carrera::orderBy('nombre')->get(),
            'grupos' => Grupo::orderBy('codigo_grupo')->get(),
            'materias' => Materia::orderBy('nombre')->get(),
            'postulantes' => $postulantes,
            'aprobados' => $aprobados,
            'reprobados' => $reprobados,
            'promedioGeneral' => $promedioGeneral,
            'gruposHabilitados' => $gruposHabilitados,
            'estadisticasMateria' => $estadisticasMateria,
            'docentesPorGrupo' => $docentesPorGrupo,
            'gruposConAprobados' => $gruposConAprobados,
        ]);
    }
}
