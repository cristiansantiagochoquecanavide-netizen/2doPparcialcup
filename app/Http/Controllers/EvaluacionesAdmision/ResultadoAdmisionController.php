<?php

namespace App\Http\Controllers\EvaluacionesAdmision;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\CupoCarreraGestion;
use App\Models\EvaluacionConfig;
use App\Models\Inscripcion;
use App\Models\ResultadoAdmision;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de CU21 y CU22: Calcular resultado final y asignar carrera admitida.
 * El promedio se calcula por materia con sus 3 evaluaciones y luego se consolida
 * como promedio final general del postulante.
 */
class ResultadoAdmisionController extends Controller
{
    public function index()
    {
        return view('evaluaciones-admision.resultados.index', [
            'inscripciones' => Inscripcion::with('postulante.carreraOpcionPrimera', 'postulante.carreraOpcionSegunda', 'gestion', 'resultadoAdmision.carreraAdmitida')
                ->orderByDesc('id_inscripcion')
                ->paginate(15),
        ]);
    }

    public function calcular($idInscripcion)
    {
        $inscripcion = Inscripcion::with('postulante', 'gestion', 'notas.evaluacion.materia')->findOrFail($idInscripcion);

        $promedio = $this->calcularPromedioFinal($inscripcion);
        if ($promedio === null) {
            return redirect()->back()->with('error', 'Faltan notas: cada materia debe tener 3 evaluaciones calificadas');
        }

        // CU21: APROBADO si promedio >= 60; REPROBADO si promedio < 60.
        $resultado = ResultadoAdmision::updateOrCreate(
            ['id_inscripcion' => $inscripcion->id_inscripcion],
            [
                'promedio_final' => $promedio,
                'estado_resultado' => $promedio >= 60 ? 'APROBADO' : 'REPROBADO',
                'fecha_resultado' => now(),
            ]
        );

        $this->registrarBitacora('CALCULAR', 'Resultado calculado para inscripcion ID ' . $inscripcion->id_inscripcion);

        return redirect()->route('resultados.index')->with('success', 'Resultado calculado: ' . $resultado->estado_resultado);
    }

    public function asignarCarrera($idResultado)
    {
        $resultado = ResultadoAdmision::with('inscripcion.postulante')->findOrFail($idResultado);

        // CU22: trabaja solo con postulantes aprobados.
        if ($resultado->estado_resultado !== 'APROBADO') {
            return redirect()->back()->with('error', 'Solo se asigna carrera a postulantes aprobados');
        }

        $inscripcion = $resultado->inscripcion;
        $postulante = $inscripcion->postulante;

        $asignacion = $this->resolverCarreraDisponible($inscripcion->id_gestion, [
            1 => $postulante->id_carrera_primera_opcion,
            2 => $postulante->id_carrera_segunda_opcion,
        ]);

        // CU22: si no hay cupo en ninguna opcion, queda aprobado sin carrera asignada.
        $resultado->update([
            'id_carrera_admitida' => $asignacion['id_carrera'],
            'orden_opcion_admitida' => $asignacion['orden'],
        ]);

        $descripcion = $asignacion['id_carrera']
            ? 'Carrera admitida asignada al resultado ID ' . $resultado->id_resultado
            : 'Resultado ID ' . $resultado->id_resultado . ' queda aprobado en espera por falta de cupo';
        $this->registrarBitacora('ASIGNAR CARRERA', $descripcion);

        return redirect()->route('resultados.index')->with('success', 'Asignacion de carrera procesada correctamente');
    }

    private function calcularPromedioFinal(Inscripcion $inscripcion): ?float
    {
        $notasPorMateria = $inscripcion->notas
            ->groupBy(fn ($nota) => $nota->evaluacion->id_materia);

        $materiasConfiguradas = EvaluacionConfig::where('id_gestion', $inscripcion->id_gestion)
            ->select('id_materia')
            ->distinct()
            ->pluck('id_materia');

        if ($materiasConfiguradas->isEmpty()) {
            return null;
        }

        $promediosMateria = [];
        foreach ($materiasConfiguradas as $idMateria) {
            $notas = $notasPorMateria->get($idMateria, collect());

            // CU21: valida que existan las 3 notas necesarias por materia.
            if ($notas->count() < 3) {
                return null;
            }

            $promediosMateria[] = round($notas->take(3)->avg('nota'), 2);
        }

        // CU21: promedio final general del postulante a partir de promedios por materia.
        return round(collect($promediosMateria)->avg(), 2);
    }

    private function resolverCarreraDisponible(int $idGestion, array $opciones): array
    {
        foreach ($opciones as $orden => $idCarrera) {
            if (!$idCarrera) {
                continue;
            }

            $cupo = CupoCarreraGestion::where('id_gestion', $idGestion)
                ->where('id_carrera', $idCarrera)
                ->first();

            if (!$cupo) {
                continue;
            }

            // CU22: cupos usados se calculan desde resultados ya admitidos para esa carrera y gestion.
            $usados = ResultadoAdmision::where('id_carrera_admitida', $idCarrera)
                ->whereHas('inscripcion', fn ($query) => $query->where('id_gestion', $idGestion))
                ->count();

            if ($usados < $cupo->cupo_maximo) {
                return ['id_carrera' => $idCarrera, 'orden' => $orden];
            }
        }

        return ['id_carrera' => null, 'orden' => null];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Resultado de Admision',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
