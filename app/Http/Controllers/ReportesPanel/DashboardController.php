<?php

namespace App\Http\Controllers\ReportesPanel;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\Postulante;
use App\Models\ResultadoAdmision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del paquete ReportesPanel.
 * CU24: Visualizar dashboard administrativo con indicadores reales.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        // CU24: indicadores reales para el panel administrativo.
        $totalInscritos = Inscripcion::count();
        $totalAprobados = ResultadoAdmision::where('estado_resultado', 'APROBADO')->count();
        $totalReprobados = ResultadoAdmision::where('estado_resultado', 'REPROBADO')->count();
        $totalGruposHabilitados = Grupo::where('estado', 'ACTIVO')->count();
        $totalPostulantes = Postulante::count();
        $promedioGeneral = round((float) ResultadoAdmision::avg('promedio_final'), 2);

        // CU24: estadisticas por materia basadas en notas registradas.
        $estadisticasMateria = Materia::query()
            ->leftJoin('evaluacion_config', 'materias.id_materia', '=', 'evaluacion_config.id_materia')
            ->leftJoin('notas', 'evaluacion_config.id_evaluacion', '=', 'notas.id_evaluacion')
            ->select('materias.nombre', DB::raw('COUNT(notas.id_nota) as total_notas'), DB::raw('AVG(notas.nota) as promedio'))
            ->groupBy('materias.id_materia', 'materias.nombre')
            ->orderBy('materias.nombre')
            ->get();

        // CU24: resumen por carrera usando postulantes de primera opcion y admitidos.
        $resumenCarreras = Carrera::query()
            ->withCount('postulantesOpcionPrimera')
            ->withCount(['resultadosAdmision as admitidos_count'])
            ->orderBy('nombre')
            ->get();

        return view('reportes-panel.dashboard', [
            'usuario' => $usuario,
            'rol' => $usuario->rol,
            'indicadores' => [
                'total_inscritos' => $totalInscritos,
                'total_aprobados' => $totalAprobados,
                'total_reprobados' => $totalReprobados,
                'total_grupos_habilitados' => $totalGruposHabilitados,
                'total_postulantes' => $totalPostulantes,
                'promedio_general' => $promedioGeneral,
            ],
            'estadisticasMateria' => $estadisticasMateria,
            'resumenCarreras' => $resumenCarreras
        ]);
    }
}
