<?php

namespace App\Http\Controllers\PostulantesInscripcion;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\GestionAcademica;
use App\Models\Inscripcion;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU9: Gestionar inscripcion.
 * Formaliza la inscripcion si el postulante tiene requisitos completos, pago confirmado y gestion activa.
 */
class InscripcionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $inscripciones = Inscripcion::with('postulante', 'gestion')
            ->when($buscar, function ($query) use ($buscar) {
                $query->whereHas('postulante', function ($subquery) use ($buscar) {
                    $subquery->where('ci', 'like', "%{$buscar}%")
                        ->orWhere('nombres', 'like', "%{$buscar}%")
                        ->orWhere('apellidos', 'like', "%{$buscar}%");
                });
            })
            ->orderByDesc('id_inscripcion')
            ->paginate(15);

        return view('postulantes-inscripcion.inscripciones.index', compact('inscripciones', 'buscar'));
    }

    public function create()
    {
        return view('postulantes-inscripcion.inscripciones.create', [
            'postulantes' => Postulante::orderBy('apellidos')->orderBy('nombres')->get(),
            'gestiones' => GestionAcademica::where('estado', 'ACTIVA')->orderByDesc('anio')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_postulante' => 'required|exists:postulantes,id_postulante',
            'id_gestion' => 'required|exists:gestion_academica,id_gestion',
        ]);

        $postulante = Postulante::with('pagos', 'requisitos')->findOrFail($validado['id_postulante']);
        $gestion = GestionAcademica::where('estado', 'ACTIVA')->findOrFail($validado['id_gestion']);

        // CU9: valida requisitos completos antes de inscribir.
        $requisitosPendientes = $postulante->requisitos()->wherePivot('presentado', false)->count();
        if ($postulante->requisitos()->count() === 0 || $requisitosPendientes > 0 || $postulante->estado !== 'VALIDADO') {
            return redirect()->back()->withInput()->with('error', 'El postulante no tiene requisitos completos o no esta validado');
        }

        // CU9: valida pago confirmado con estado PAGADO.
        if (!$postulante->pagos()->where('estado_pago', 'PAGADO')->exists()) {
            return redirect()->back()->withInput()->with('error', 'El postulante no tiene pago confirmado');
        }

        // CU9: evita inscripcion duplicada en la misma gestion.
        if (Inscripcion::where('id_postulante', $postulante->id_postulante)->where('id_gestion', $gestion->id_gestion)->exists()) {
            return redirect()->back()->withInput()->with('error', 'El postulante ya esta inscrito en esta gestion');
        }

        $inscripcion = Inscripcion::create([
            'id_postulante' => $postulante->id_postulante,
            'id_gestion' => $gestion->id_gestion,
            'estado_inscripcion' => 'INSCRITO',
        ]);

        $this->registrarBitacora('CREAR', 'Inscripcion ID ' . $inscripcion->id_inscripcion . ' registrada');

        return redirect()->route('inscripciones.index')->with('success', 'Inscripcion registrada correctamente');
    }

    public function show($id)
    {
        return view('postulantes-inscripcion.inscripciones.show', [
            'inscripcion' => Inscripcion::with('postulante', 'gestion', 'grupoEstudiante.grupo')->findOrFail($id),
        ]);
    }

    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->delete();

        $this->registrarBitacora('ELIMINAR', 'Inscripcion ID ' . $id . ' eliminada');

        return redirect()->route('inscripciones.index')->with('success', 'Inscripcion eliminada correctamente');
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Inscripciones',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
