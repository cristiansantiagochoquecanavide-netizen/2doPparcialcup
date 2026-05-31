<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    private const ESTADOS_CONTRATACION = 'ACTIVO,INACTIVO,LICENCIA,JUBILADO';

    /**
     * Listar todos los docentes
     */
    public function index()
    {
        try {
            $docentes = Docente::withCount('cargasHorarias')->paginate(15);

            return view('docentes.index', [
                'docentes' => $docentes
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar docentes: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear docente
     */
    public function create()
    {
        try {
            return view('docentes.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo docente
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate($this->rules());
            $validado['fecha_registro'] = now();

            $docente = Docente::create($validado);

            $this->registrarBitacora(
                'CREAR',
                'Docente ' . $docente->nombres . ' ' . $docente->apellidos . ' registrado'
            );

            return redirect()->route('docentes.show', $docente->id_docente)
                ->with('success', 'Docente registrado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar docente: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un docente
     */
    public function show($id)
    {
        try {
            $docente = Docente::with('cargasHorarias.grupo', 'cargasHorarias.materia', 'cargasHorarias.aula')
                ->findOrFail($id);

            $estadisticas = [
                'cargas_horarias' => $docente->cargasHorarias()->count(),
                'materias_diferentes' => $docente->cargasHorarias()->distinct('id_materia')->count(),
                'grupos_atiende' => $docente->cargasHorarias()->distinct('id_grupo')->count()
            ];

            return view('docentes.show', [
                'docente' => $docente,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Docente no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar docente
     */
    public function edit($id)
    {
        try {
            $docente = Docente::findOrFail($id);

            return view('docentes.edit', [
                'docente' => $docente
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Docente no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar docente
     */
    public function update(Request $request, $id)
    {
        try {
            $docente = Docente::findOrFail($id);
            $validado = $request->validate($this->rules($id));

            $docente->update($validado);

            $this->registrarBitacora(
                'ACTUALIZAR',
                'Docente ' . $docente->nombres . ' ' . $docente->apellidos . ' actualizado'
            );

            return redirect()->route('docentes.show', $id)
                ->with('success', 'Docente actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar docente: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar docente
     */
    public function destroy($id)
    {
        try {
            $docente = Docente::findOrFail($id);
            $nombreDocente = $docente->nombres . ' ' . $docente->apellidos;

            if ($docente->cargasHorarias()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el docente porque tiene cargas horarias asignadas');
            }

            $this->registrarBitacora(
                'ELIMINAR',
                'Docente ' . $nombreDocente . ' eliminado del sistema'
            );

            $docente->delete();

            return redirect()->route('docentes.index')
                ->with('success', 'Docente eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar docente: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de contratacion del docente
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $docente = Docente::findOrFail($id);

            $validado = $request->validate([
                'estado_contratacion' => 'required|in:' . self::ESTADOS_CONTRATACION
            ]);

            $estadoAnterior = $docente->estado_contratacion;
            $docente->update(['estado_contratacion' => $validado['estado_contratacion']]);

            $this->registrarBitacora(
                'CAMBIAR ESTADO',
                'Docente ' . $docente->nombres . ' cambio estado: ' . $estadoAnterior . ' -> ' . $validado['estado_contratacion']
            );

            return redirect()->back()
                ->with('success', 'Estado del docente actualizado');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    /**
     * Filtrar docentes por estado de contratacion
     */
    public function filtrar(Request $request)
    {
        try {
            $query = Docente::query();

            if ($request->filled('estado_contratacion')) {
                $query->where('estado_contratacion', $request->estado_contratacion);
            }

            if ($request->has('tiene_maestria') && $request->tiene_maestria !== '') {
                $query->where('tiene_maestria', (bool) $request->tiene_maestria);
            }

            if ($request->has('tiene_diplomado_educacion_superior') && $request->tiene_diplomado_educacion_superior !== '') {
                $query->where('tiene_diplomado_educacion_superior', (bool) $request->tiene_diplomado_educacion_superior);
            }

            if ($request->filled('buscar')) {
                $buscar = $request->buscar;
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombres', 'like', '%' . $buscar . '%')
                        ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                        ->orWhere('ci', 'like', '%' . $buscar . '%')
                        ->orWhere('correo', 'like', '%' . $buscar . '%');
                });
            }

            $docentes = $query->paginate(15);

            return view('docentes.filtrar', [
                'docentes' => $docentes,
                'filtros' => $request->all()
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al filtrar docentes: ' . $e->getMessage());
        }
    }

    private function rules(?int $id = null): array
    {
        $uniqueCi = 'unique:docentes,ci';
        $uniqueCorreo = 'unique:docentes,correo';

        if ($id !== null) {
            $uniqueCi .= ',' . $id . ',id_docente';
            $uniqueCorreo .= ',' . $id . ',id_docente';
        }

        return [
            'ci' => 'required|string|' . $uniqueCi . '|max:20',
            'nombres' => 'required|string|max:80',
            'apellidos' => 'required|string|max:80',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|' . $uniqueCorreo . '|max:100',
            'profesional_area' => 'nullable|string|max:100',
            'tiene_maestria' => 'required|boolean',
            'tiene_diplomado_educacion_superior' => 'required|boolean',
            'estado_contratacion' => 'required|in:' . self::ESTADOS_CONTRATACION
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Gestion de Docentes',
            'accion' => $accion,
            'descripcion' => $descripcion
        ]);
    }
}
