<?php

namespace App\Http\Controllers\EvaluacionesAdmision;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\EvaluacionConfig;
use App\Models\GestionAcademica;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Controlador del CU19: Gestionar evaluaciones.
 * Configura 3 evaluaciones por materia y gestion academica.
 */
class EvaluacionController extends Controller
{
    public function index()
    {
        return view('evaluaciones-admision.evaluaciones.index', [
            'evaluaciones' => EvaluacionConfig::with('gestion', 'materia')
                ->orderByDesc('id_gestion')
                ->orderBy('id_materia')
                ->orderBy('numero_evaluacion')
                ->paginate(15),
        ]);
    }

    public function create()
    {
        return view('evaluaciones-admision.evaluaciones.create', $this->formData(new EvaluacionConfig()));
    }

    public function store(Request $request)
    {
        $validado = $request->validate($this->rules());
        $evaluacion = EvaluacionConfig::create($validado);

        $this->registrarBitacora('CREAR', 'Evaluacion ' . $evaluacion->numero_evaluacion . ' configurada');

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluacion configurada correctamente');
    }

    public function edit($id)
    {
        return view('evaluaciones-admision.evaluaciones.edit', $this->formData(EvaluacionConfig::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        $evaluacion = EvaluacionConfig::findOrFail($id);
        $evaluacion->update($request->validate($this->rules($evaluacion)));

        $this->registrarBitacora('ACTUALIZAR', 'Evaluacion ID ' . $evaluacion->id_evaluacion . ' actualizada');

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluacion actualizada correctamente');
    }

    public function destroy($id)
    {
        $evaluacion = EvaluacionConfig::findOrFail($id);
        if ($evaluacion->notas()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar una evaluacion con notas registradas');
        }

        $evaluacion->delete();
        $this->registrarBitacora('ELIMINAR', 'Evaluacion ID ' . $id . ' eliminada');

        return redirect()->route('evaluaciones.index')->with('success', 'Evaluacion eliminada correctamente');
    }

    private function rules(?EvaluacionConfig $evaluacion = null): array
    {
        return [
            'id_gestion' => 'required|exists:gestion_academica,id_gestion',
            'id_materia' => 'required|exists:materias,id_materia',
            // CU19: solo se permiten 3 evaluaciones por materia.
            'numero_evaluacion' => [
                'required',
                'integer',
                'min:1',
                'max:3',
                Rule::unique('evaluacion_config', 'numero_evaluacion')
                    ->where(fn ($query) => $query
                        ->where('id_gestion', request('id_gestion'))
                        ->where('id_materia', request('id_materia')))
                    ->ignore($evaluacion?->id_evaluacion, 'id_evaluacion'),
            ],
            'porcentaje' => 'required|numeric|min:0.01|max:100',
        ];
    }

    private function formData(EvaluacionConfig $evaluacion): array
    {
        return [
            'evaluacion' => $evaluacion,
            'gestiones' => GestionAcademica::orderByDesc('anio')->get(),
            'materias' => Materia::where('estado', 'ACTIVA')->orderBy('nombre')->get(),
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Evaluaciones',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
