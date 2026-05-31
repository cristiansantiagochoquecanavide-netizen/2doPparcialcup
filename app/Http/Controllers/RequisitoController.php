<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Postulante;
use App\Models\Requisito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisitoController extends Controller
{
    /**
     * Listar todos los requisitos
     */
    public function index()
    {
        try {
            $requisitos = Requisito::withCount('postulantes')->paginate(15);

            return view('requisitos.index', [
                'requisitos' => $requisitos
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar requisitos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear requisito
     */
    public function create()
    {
        try {
            return view('requisitos.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nuevo requisito
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate($this->rules());
            $requisito = Requisito::create($validado);

            $this->registrarBitacora(
                'CREAR',
                'Requisito ' . $requisito->nombre . ' creado exitosamente'
            );

            return redirect()->route('requisitos.index')
                ->with('success', 'Requisito creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear requisito: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un requisito
     */
    public function show($id)
    {
        try {
            $requisito = Requisito::with('postulantes')->findOrFail($id);

            return view('requisitos.show', [
                'requisito' => $requisito
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Requisito no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar requisito
     */
    public function edit($id)
    {
        try {
            $requisito = Requisito::findOrFail($id);

            return view('requisitos.edit', [
                'requisito' => $requisito
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Requisito no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar requisito
     */
    public function update(Request $request, $id)
    {
        try {
            $requisito = Requisito::findOrFail($id);
            $validado = $request->validate($this->rules($id));

            $requisito->update($validado);

            $this->registrarBitacora(
                'ACTUALIZAR',
                'Requisito ' . $requisito->nombre . ' actualizado'
            );

            return redirect()->route('requisitos.index')
                ->with('success', 'Requisito actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar requisito: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar requisito
     */
    public function destroy($id)
    {
        try {
            $requisito = Requisito::findOrFail($id);
            $nombreRequisito = $requisito->nombre;

            if ($requisito->postulantes()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el requisito porque ya fue asignado a postulantes');
            }

            $this->registrarBitacora(
                'ELIMINAR',
                'Requisito ' . $nombreRequisito . ' eliminado del sistema'
            );

            $requisito->delete();

            return redirect()->route('requisitos.index')
                ->with('success', 'Requisito eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar requisito: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para validar requisitos de un postulante
     */
    public function validarPostulante($id)
    {
        try {
            $postulante = Postulante::with('requisitos')->findOrFail($id);
            $requisitosActivos = Requisito::where('estado', 'ACTIVO')->orderBy('nombre')->get();

            return view('requisitos.validar-postulante', [
                'postulante' => $postulante,
                'requisitosActivos' => $requisitosActivos
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar validacion: ' . $e->getMessage());
        }
    }

    /**
     * Guardar validacion de requisitos de un postulante
     */
    public function guardarValidacion(Request $request, $id)
    {
        try {
            $postulante = Postulante::findOrFail($id);

            $validado = $request->validate([
                'requisitos' => 'nullable|array',
                'requisitos.*.id_requisito' => 'required|exists:requisitos,id_requisito',
                'requisitos.*.presentado' => 'required|boolean',
                'requisitos.*.fecha_presentacion' => 'nullable|date|before_or_equal:today',
                'requisitos.*.observacion' => 'nullable|string|max:255'
            ]);

            $datosSync = [];
            foreach ($validado['requisitos'] ?? [] as $req) {
                $presentado = (bool) $req['presentado'];
                $datosSync[$req['id_requisito']] = [
                    'presentado' => $presentado,
                    'fecha_presentacion' => $presentado ? ($req['fecha_presentacion'] ?? now()->toDateString()) : null,
                    'observacion' => $req['observacion'] ?? null
                ];
            }

            $postulante->requisitos()->sync($datosSync);

            $requisitosObligatorios = Requisito::where('estado', 'ACTIVO')
                ->where('obligatorio', true)
                ->pluck('id_requisito');

            $requisitosPresentados = $postulante->requisitos()
                ->wherePivot('presentado', true)
                ->whereIn('requisitos.id_requisito', $requisitosObligatorios)
                ->pluck('requisitos.id_requisito');

            $estado = $requisitosObligatorios->diff($requisitosPresentados)->isEmpty()
                ? 'VALIDADO'
                : 'REGISTRADO';

            $postulante->update(['estado' => $estado]);

            $this->registrarBitacora(
                'VALIDAR POSTULANTE',
                'Requisitos del postulante ' . $postulante->nombres . ' validados. Estado: ' . $estado
            );

            return redirect()->route('postulantes.show', $id)
                ->with('success', 'Requisitos validados. Estado del postulante: ' . $estado);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar validacion: ' . $e->getMessage());
        }
    }

    private function rules(?int $id = null): array
    {
        $uniqueNombre = 'unique:requisitos,nombre';

        if ($id !== null) {
            $uniqueNombre .= ',' . $id . ',id_requisito';
        }

        return [
            'nombre' => 'required|string|' . $uniqueNombre . '|max:100',
            'descripcion' => 'nullable|string|max:200',
            'obligatorio' => 'required|boolean',
            'estado' => 'required|in:ACTIVO,INACTIVO'
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Gestion de Requisitos',
            'accion' => $accion,
            'descripcion' => $descripcion
        ]);
    }
}
