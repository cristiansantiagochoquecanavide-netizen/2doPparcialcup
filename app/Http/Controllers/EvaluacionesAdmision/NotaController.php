<?php

namespace App\Http\Controllers\EvaluacionesAdmision;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\EvaluacionConfig;
use App\Models\Inscripcion;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Controlador del CU20: Gestionar notas.
 * Registra y edita notas entre 0 y 100, evitando duplicidad por inscripcion/evaluacion.
 */
class NotaController extends Controller
{
    public function index()
    {
        return view('evaluaciones-admision.notas.index', [
            'notas' => Nota::with('inscripcion.postulante', 'evaluacion.materia', 'evaluacion.gestion')
                ->orderByDesc('id_nota')
                ->paginate(15),
        ]);
    }

    public function create()
    {
        return view('evaluaciones-admision.notas.create', $this->formData(new Nota()));
    }

    public function store(Request $request)
    {
        $validado = $request->validate($this->rules());
        $error = $this->validarDocenteCarga($validado['id_inscripcion'], $validado['id_evaluacion']);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $nota = Nota::create($validado);
        $this->registrarBitacora('CREAR', 'Nota ID ' . $nota->id_nota . ' registrada');

        return redirect()->route('notas.index')->with('success', 'Nota registrada correctamente');
    }

    public function edit($id)
    {
        return view('evaluaciones-admision.notas.edit', $this->formData(Nota::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);
        $validado = $request->validate($this->rules($nota));
        $error = $this->validarDocenteCarga($validado['id_inscripcion'], $validado['id_evaluacion']);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $nota->update($validado);
        $this->registrarBitacora('ACTUALIZAR', 'Nota ID ' . $nota->id_nota . ' actualizada');

        return redirect()->route('notas.index')->with('success', 'Nota actualizada correctamente');
    }

    private function rules(?Nota $nota = null): array
    {
        return [
            'id_inscripcion' => 'required|exists:inscripciones,id_inscripcion',
            'id_evaluacion' => [
                'required',
                'exists:evaluacion_config,id_evaluacion',
                Rule::unique('notas', 'id_evaluacion')
                    ->where(fn ($query) => $query->where('id_inscripcion', request('id_inscripcion')))
                    ->ignore($nota?->id_nota, 'id_nota'),
            ],
            'nota' => 'required|numeric|min:0|max:100',
        ];
    }

    private function validarDocenteCarga(int $idInscripcion, int $idEvaluacion): ?string
    {
        $docente = Docente::where('correo', Auth::user()?->correo)->first();
        if (!$docente) {
            return null;
        }

        $inscripcion = Inscripcion::with('grupoEstudiante')->findOrFail($idInscripcion);
        $evaluacion = EvaluacionConfig::findOrFail($idEvaluacion);

        // CU20: si el usuario autenticado es docente, solo puede registrar notas de su carga horaria.
        $permitido = CargaHoraria::where('id_docente', $docente->id_docente)
            ->where('id_grupo', $inscripcion->grupoEstudiante?->id_grupo)
            ->where('id_materia', $evaluacion->id_materia)
            ->exists();

        return $permitido ? null : 'El docente no tiene carga horaria para esta materia y grupo';
    }

    private function formData(Nota $nota): array
    {
        return [
            'nota' => $nota,
            'inscripciones' => Inscripcion::with('postulante', 'gestion')->orderByDesc('id_inscripcion')->get(),
            'evaluaciones' => EvaluacionConfig::with('materia', 'gestion')->orderByDesc('id_gestion')->get(),
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Notas',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
