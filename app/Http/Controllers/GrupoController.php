<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\GestionAcademica;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    /**
     * Listar todos los grupos
     */
    public function index()
    {
        try {
            $grupos = Grupo::with('gestion')
                ->withCount('estudiantesGrupo')
                ->paginate(15);

            return view('grupos.index', [
                'grupos' => $grupos
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar grupos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear grupo
     */
    public function create()
    {
        try {
            return view('grupos.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo grupo
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate($this->rules());
            $validado['id_gestion'] = $this->gestionActiva()->id_gestion;
            $grupo = Grupo::create($validado);

            $this->registrarBitacora(
                'CREAR',
                'Grupo ' . $grupo->codigo_grupo . ' creado exitosamente'
            );

            return redirect()->route('grupos.show', $grupo->id_grupo)
                ->with('success', 'Grupo creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear grupo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un grupo
     */
    public function show($id)
    {
        try {
            $grupo = Grupo::with('gestion', 'estudiantesGrupo.inscripcion.postulante', 'cargasHorarias')
                ->findOrFail($id);

            $estudiantes = $grupo->estudiantesGrupo->count();
            $ocupacion = [
                'estudiantes' => $estudiantes,
                'cupo_maximo' => $grupo->cupo_maximo,
                'disponibles' => max($grupo->cupo_maximo - $estudiantes, 0),
                'porcentaje' => $grupo->cupo_maximo > 0 ? round(($estudiantes / $grupo->cupo_maximo) * 100) : 0
            ];

            return view('grupos.show', [
                'grupo' => $grupo,
                'ocupacion' => $ocupacion
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Grupo no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar grupo
     */
    public function edit($id)
    {
        try {
            $grupo = Grupo::findOrFail($id);

            return view('grupos.edit', [
                'grupo' => $grupo
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Grupo no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar grupo
     */
    public function update(Request $request, $id)
    {
        try {
            $grupo = Grupo::findOrFail($id);
            $validado = $request->validate($this->rules($grupo));
            $validado['id_gestion'] = $grupo->id_gestion;

            $estudiantesActuales = $grupo->estudiantesGrupo()->count();
            if ($validado['cupo_maximo'] < $estudiantesActuales) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'El cupo maximo no puede ser menor a la cantidad de estudiantes actuales (' . $estudiantesActuales . ')');
            }

            $grupo->update($validado);

            $this->registrarBitacora(
                'ACTUALIZAR',
                'Grupo ' . $grupo->codigo_grupo . ' actualizado'
            );

            return redirect()->route('grupos.show', $id)
                ->with('success', 'Grupo actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar grupo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar grupo
     */
    public function destroy($id)
    {
        try {
            $grupo = Grupo::findOrFail($id);
            $codigoGrupo = $grupo->codigo_grupo;

            if ($grupo->estudiantesGrupo()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el grupo porque tiene estudiantes asignados');
            }

            if ($grupo->cargasHorarias()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el grupo porque tiene cargas horarias asignadas');
            }

            $this->registrarBitacora(
                'ELIMINAR',
                'Grupo ' . $codigoGrupo . ' eliminado del sistema'
            );

            $grupo->delete();

            return redirect()->route('grupos.index')
                ->with('success', 'Grupo eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar grupo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar calculadora de grupos
     */
    public function calculadora()
    {
        try {
            return view('grupos.calcular', [
                'gestiones' => $this->gestionesDisponibles()
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar calculadora: ' . $e->getMessage());
        }
    }

    /**
     * Calcular la cantidad de grupos necesarios para una gestion.
     */
    public function calcularCantidadGrupos(Request $request)
    {
        try {
            $validado = $request->validate([
                'id_gestion' => 'required|exists:gestion_academica,id_gestion',
                'cantidad_postulantes' => 'required|integer|min:1',
                'cupo_maximo_grupo' => 'required|integer|min:1|max:200'
            ]);

            $gestion = GestionAcademica::findOrFail($validado['id_gestion']);
            $cantidadPostulantes = $validado['cantidad_postulantes'];
            $cupoMaximo = $validado['cupo_maximo_grupo'];
            $gruposNecesarios = (int) ceil($cantidadPostulantes / $cupoMaximo);
            $distribucion = [];
            $postulantesRestantes = $cantidadPostulantes;

            for ($i = 1; $i <= $gruposNecesarios; $i++) {
                $estudiantesEnGrupo = min($postulantesRestantes, $cupoMaximo);
                $distribucion[] = [
                    'grupo' => $i,
                    'estudiantes' => $estudiantesEnGrupo,
                    'disponibles' => $cupoMaximo - $estudiantesEnGrupo
                ];
                $postulantesRestantes -= $estudiantesEnGrupo;
            }

            return response()->json([
                'success' => true,
                'gestion' => $gestion->nombre,
                'cantidad_postulantes' => $cantidadPostulantes,
                'cupo_maximo_grupo' => $cupoMaximo,
                'grupos_necesarios' => $gruposNecesarios,
                'distribucion' => $distribucion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al calcular grupos: ' . $e->getMessage()
            ], 400);
        }
    }

    private function rules(?Grupo $grupo = null): array
    {
        $idGestion = $grupo?->id_gestion ?? $this->gestionActiva()->id_gestion;

        return [
            'codigo_grupo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('grupos', 'codigo_grupo')
                    ->where(fn ($query) => $query->where('id_gestion', $idGestion))
                    ->ignore($grupo?->id_grupo, 'id_grupo'),
            ],
            'cupo_maximo' => 'required|integer|min:1|max:200',
            'estado' => 'required|in:ACTIVO,INACTIVO'
        ];
    }

    private function gestionActiva(): GestionAcademica
    {
        $gestion = GestionAcademica::where('estado', 'ACTIVA')
            ->orderByDesc('anio')
            ->orderByDesc('id_gestion')
            ->first();

        if ($gestion) {
            return $gestion;
        }

        $anio = (int) now()->format('Y');

        return GestionAcademica::create([
            'nombre' => 'Gestion ' . $anio,
            'anio' => $anio,
            'periodo' => 'I',
            'fecha_inicio' => now()->startOfYear()->toDateString(),
            'fecha_fin' => now()->endOfYear()->toDateString(),
            'estado' => 'ACTIVA',
        ]);
    }

    private function gestionesDisponibles(?int $idGestionActual = null)
    {
        return GestionAcademica::query()
            ->where(function ($query) use ($idGestionActual) {
                $query->where('estado', 'ACTIVA');

                if ($idGestionActual !== null) {
                    $query->orWhere('id_gestion', $idGestionActual);
                }
            })
            ->orderBy('nombre')
            ->get();
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Gestion de Grupos',
            'accion' => $accion,
            'descripcion' => $descripcion
        ]);
    }
}
