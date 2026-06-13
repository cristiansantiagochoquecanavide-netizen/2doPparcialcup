<?php

namespace App\Http\Controllers\PostulantesInscripcion;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Postulante;
use App\Models\Requisito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU7: Validar requisitos del postulante.
 * Administra requisitos y registra la validacion documental de postulantes.
 */
class RequisitoController extends Controller
{
    /**
     * Muestra el listado de requisitos registrados.
     * Corresponde al flujo ListarRequisitos() del CU7.
     */
    public function index()
    {
        try {
            $requisitos = Requisito::withCount('postulantes')->paginate(15);

            return view('postulantes-inscripcion.requisitos.index', [
                'requisitos' => $requisitos
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar requisitos: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de registro de requisito.
     * Corresponde al flujo CrearRequisito() del CU7.
     */
    public function create()
    {
        try {
            return view('postulantes-inscripcion.requisitos.create');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * Registra un nuevo requisito documental.
     * Corresponde al flujo GuardarRequisito() del CU7.
     */
    public function store(Request $request)
    {
        try {
            $validado = $request->validate($this->rules());
            $requisito = Requisito::create($validado);

            // Registra en bitacora la creacion de un requisito, correspondiente al CU7.
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
     * Muestra la informacion y postulantes asociados al requisito.
     * Corresponde al flujo ConsultarRequisito() del CU7.
     */
    public function show($id)
    {
        try {
            $requisito = Requisito::with('postulantes')->findOrFail($id);

            return view('postulantes-inscripcion.requisitos.show', [
                'requisito' => $requisito
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Requisito no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edicion de requisito.
     * Corresponde al flujo EditarRequisito() del CU7.
     */
    public function edit($id)
    {
        try {
            $requisito = Requisito::findOrFail($id);

            return view('postulantes-inscripcion.requisitos.edit', [
                'requisito' => $requisito
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Requisito no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza datos, obligatoriedad y estado del requisito.
     * Corresponde al flujo ActualizarRequisito() del CU7.
     */
    public function update(Request $request, $id)
    {
        try {
            $requisito = Requisito::findOrFail($id);
            $validado = $request->validate($this->rules($id));

            $requisito->update($validado);

            // Registra en bitacora la actualizacion de un requisito, correspondiente al CU7.
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
     * Elimina un requisito sin postulantes asociados.
     * Corresponde al flujo EliminarRequisito() del CU7.
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

            // Registra en bitacora la eliminacion de un requisito, correspondiente al CU7.
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
     * Muestra la matriz de requisitos activos para un postulante.
     * Corresponde al flujo ValidarRequisitosPostulante() del CU7.
     */
    public function validarPostulante($id)
    {
        try {
            $postulante = Postulante::with('requisitos')->findOrFail($id);
            $requisitosActivos = Requisito::where('estado', 'ACTIVO')->orderBy('nombre')->get();

            return view('postulantes-inscripcion.requisitos.validar-postulante', [
                'postulante' => $postulante,
                'requisitosActivos' => $requisitosActivos
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar validacion: ' . $e->getMessage());
        }
    }

    /**
     * Guarda la validacion documental y actualiza el estado del postulante.
     * Corresponde al flujo GuardarValidacionRequisitos() del CU7.
     */
    public function guardarValidacion(Request $request, $id)
    {
        try {
            $postulante = Postulante::findOrFail($id);

            // CU7: aqui se valida que el postulante cumpla documentos como titulo de bachiller y otros requisitos.
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

            // Si todos los requisitos obligatorios activos fueron presentados, el postulante queda VALIDADO.
            // Ese estado es la condicion previa para registrar pago e inscripcion en los CU8 y CU9.
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

            // Registra en bitacora la validacion de requisitos, correspondiente al CU7.
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
