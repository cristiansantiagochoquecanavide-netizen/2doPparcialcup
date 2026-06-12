<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Carrera;
use App\Models\Requisito;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Controlador del CU6: Gestionar postulantes.
 * Permite registrar, consultar, actualizar, eliminar y cambiar estado de postulantes.
 */
class PostulanteController extends Controller
{
    /**
     * Muestra el listado de postulantes registrados.
     * Corresponde al flujo ListarPostulantes() del CU6.
     */
    public function index()
    {
        try {
            // Captura el texto de busqueda enviado desde postulantes/index.blade.php.
            $buscar = request('buscar');

            $postulantes = Postulante::with('carreraOpcionPrimera', 'carreraOpcionSegunda')
                // Filtra por CI, nombres, apellidos o nombre de carrera cuando el usuario escribe en el buscador.
                ->when($buscar, function ($query, $buscar) {
                    $query->where(function ($query) use ($buscar) {
                        $query->where('ci', 'like', "%{$buscar}%")
                            ->orWhere('nombres', 'like', "%{$buscar}%")
                            ->orWhere('apellidos', 'like', "%{$buscar}%")
                            ->orWhereHas('carreraOpcionPrimera', function ($query) use ($buscar) {
                                $query->where('nombre', 'like', "%{$buscar}%");
                            })
                            ->orWhereHas('carreraOpcionSegunda', function ($query) use ($buscar) {
                                $query->where('nombre', 'like', "%{$buscar}%");
                            });
                    });
                })
                ->orderBy('apellidos')
                ->orderBy('nombres')
                ->paginate(15);
            
            return view('postulantes.index', [
                'postulantes' => $postulantes,
                'buscar' => $buscar
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar postulantes: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de registro de postulante.
     * Corresponde al flujo CrearPostulante() del CU6.
     */
    public function create()
    {
        try {
            $carreras = Carrera::where('estado', 'ACTIVA')->get();
            
            return view('postulantes.create', [
                'carreras' => $carreras
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cargar formulario: ' . $e->getMessage());
        }
    }

    /**
     * CU25: muestra el formulario publico de pre-registro del postulante.
     */
    public function preRegistro()
    {
        $carreras = Carrera::where('estado', 'ACTIVA')
            ->orderBy('nombre')
            ->get();

        return view('postulantes.pre-registro', [
            'carreras' => $carreras
        ]);
    }

    /**
     * CU25: guarda el pre-registro publico y deja al postulante pendiente de validacion.
     */
    public function guardarPreRegistro(Request $request)
    {
        $request->merge(['estado' => 'PENDIENTE_VALIDACION']);

        $validado = $request->validate($this->reglasValidacion());

        // CU25: el postulante no queda validado automaticamente; el administrador revisa requisitos despues.
        $validado['estado'] = 'PENDIENTE_VALIDACION';

        Postulante::create($validado);

        return redirect()->route('postulantes.pre-registro')
            ->with('success', 'Pre-registro enviado correctamente. Sus requisitos seran revisados por administracion.');
    }

    /**
     * Registra un nuevo postulante con sus opciones de carrera.
     * Corresponde al flujo GuardarPostulante() del CU6.
     */
    public function store(Request $request)
    {
        // Valida todos los campos obligatorios antes de crear el postulante.
        $validado = $request->validate($this->reglasValidacion());

        try {
            $postulante = Postulante::create($validado);

            // Registra en bitacora la creacion de un postulante, correspondiente al CU6.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'CREAR',
                'descripcion' => 'Postulante ' . $postulante->nombres . ' ' . $postulante->apellidos . ' registrado'
            ]);

            return redirect()->route('postulantes.show', $postulante->id_postulante)
                ->with('success', 'Postulante registrado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar postulante: ' . $e->getMessage());
        }
    }

    /**
     * Muestra datos, requisitos, pagos e inscripciones del postulante.
     * Corresponde al flujo ConsultarPostulante() del CU6.
     */
    public function show($id)
    {
        try {
            $postulante = Postulante::with(
                'carreraOpcionPrimera',
                'carreraOpcionSegunda',
                'requisitos',
                'pagos',
                'inscripciones'
            )->findOrFail($id);
            
            return view('postulantes.show', [
                'postulante' => $postulante
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Postulante no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario de edicion del postulante.
     * Corresponde al flujo EditarPostulante() del CU6.
     */
    public function edit($id)
    {
        try {
            $postulante = Postulante::findOrFail($id);
            $carreras = Carrera::where('estado', 'ACTIVA')->get();
            
            return view('postulantes.edit', [
                'postulante' => $postulante,
                'carreras' => $carreras
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Postulante no encontrado: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza los datos personales y academicos del postulante.
     * Corresponde al flujo ActualizarPostulante() del CU6.
     */
    public function update(Request $request, $id)
    {
        // Reutiliza las mismas reglas, ignorando el CI/correo del postulante actual para permitir editarlo.
        $validado = $request->validate($this->reglasValidacion($id));

        try {
            $postulante = Postulante::findOrFail($id);

            $postulante->update($validado);

            // Registra en bitacora la actualizacion de un postulante, correspondiente al CU6.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'ACTUALIZAR',
                'descripcion' => 'Postulante ' . $postulante->nombres . ' ' . $postulante->apellidos . ' actualizado'
            ]);

            return redirect()->route('postulantes.show', $id)
                ->with('success', 'Postulante actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar postulante: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un postulante sin inscripciones asociadas.
     * Corresponde al flujo EliminarPostulante() del CU6.
     */
    public function destroy($id)
    {
        try {
            $postulante = Postulante::findOrFail($id);
            $nombrePostulante = $postulante->nombres . ' ' . $postulante->apellidos;

            // Verificar si tiene inscripciones
            if ($postulante->inscripciones()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el postulante porque tiene inscripciones');
            }

            // Registra en bitacora la eliminacion de un postulante, correspondiente al CU6.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'ELIMINAR',
                'descripcion' => 'Postulante ' . $nombrePostulante . ' eliminado del sistema'
            ]);

            $postulante->delete();

            return redirect()->route('postulantes.index')
                ->with('success', 'Postulante eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar postulante: ' . $e->getMessage());
        }
    }

    /**
     * Cambia el estado administrativo del postulante.
     * Corresponde al flujo CambiarEstadoPostulante() del CU6.
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $postulante = Postulante::findOrFail($id);

            $validado = $request->validate([
                'estado' => 'required|in:PENDIENTE_VALIDACION,REGISTRADO,VALIDADO,INSCRITO,RECHAZADO'
            ]);

            $estadoAnterior = $postulante->estado;
            $postulante->update(['estado' => $validado['estado']]);

            // Registra en bitacora el cambio de estado del postulante, correspondiente al CU6.
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'modulo' => 'Gestión de Postulantes',
                'accion' => 'CAMBIAR ESTADO',
                'descripcion' => 'Postulante ' . $postulante->nombres . ' cambió de estado: ' . $estadoAnterior . ' → ' . $validado['estado']
            ]);

            return redirect()->back()
                ->with('success', 'Estado del postulante actualizado');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }

    private function reglasValidacion(?int $idPostulante = null): array
    {
        // Reglas comunes para crear y editar: impiden campos obligatorios vacios y duplicados.
        return [
            'ci' => [
                'required',
                'string',
                'max:20',
                Rule::unique('postulantes', 'ci')->ignore($idPostulante, 'id_postulante'),
            ],
            'nombres' => 'required|string|max:80',
            'apellidos' => 'required|string|max:80',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => [
                'required',
                'email',
                'max:100',
                Rule::unique('postulantes', 'correo')->ignore($idPostulante, 'id_postulante'),
            ],
            'colegio_procedencia' => 'nullable|string|max:120',
            'ciudad' => 'nullable|string|max:80',
            'id_carrera_primera_opcion' => 'required|exists:carreras,id_carrera',
            'id_carrera_segunda_opcion' => 'nullable|exists:carreras,id_carrera',
            'titulo_bachiller' => 'nullable|string|max:120',
            'otros_requisitos' => 'nullable|string|max:255',
            'estado' => 'required|in:PENDIENTE_VALIDACION,REGISTRADO,VALIDADO,INSCRITO,RECHAZADO',
        ];
    }
}
