<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Bitacora;
use App\Models\CargaHoraria;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU17: Gestionar carga horaria.
 * Asigna grupo, materia, docente, aula, dia y horas evitando cruces.
 */
class CargaHorariaController extends Controller
{
    public function index()
    {
        return view('gestion-academica.carga-horaria.index', [
            'cargas' => CargaHoraria::with('grupo.gestion', 'materia', 'docente', 'aula')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('gestion-academica.carga-horaria.create', $this->formData(new CargaHoraria()));
    }

    public function store(Request $request)
    {
        $validado = $request->validate($this->rules());
        $error = $this->validarCrucesYCargaDocente($validado);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $carga = CargaHoraria::create($validado);
        $this->registrarBitacora('CREAR', 'Carga horaria ID ' . $carga->id_carga_horaria . ' creada');

        return redirect()->route('carga-horaria.index')->with('success', 'Carga horaria registrada correctamente');
    }

    public function edit($id)
    {
        return view('gestion-academica.carga-horaria.edit', $this->formData(CargaHoraria::findOrFail($id)));
    }

    public function update(Request $request, $id)
    {
        $carga = CargaHoraria::findOrFail($id);
        $validado = $request->validate($this->rules());
        $error = $this->validarCrucesYCargaDocente($validado, $carga->id_carga_horaria);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $carga->update($validado);
        $this->registrarBitacora('ACTUALIZAR', 'Carga horaria ID ' . $carga->id_carga_horaria . ' actualizada');

        return redirect()->route('carga-horaria.index')->with('success', 'Carga horaria actualizada correctamente');
    }

    public function destroy($id)
    {
        $carga = CargaHoraria::findOrFail($id);
        if ($carga->asistenciasClase()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar una carga horaria con asistencias registradas');
        }

        $carga->delete();
        $this->registrarBitacora('ELIMINAR', 'Carga horaria ID ' . $id . ' eliminada');

        return redirect()->route('carga-horaria.index')->with('success', 'Carga horaria eliminada correctamente');
    }

    private function rules(): array
    {
        return [
            'id_grupo' => 'required|exists:grupos,id_grupo',
            'id_materia' => 'required|exists:materias,id_materia',
            'id_docente' => 'required|exists:docentes,id_docente',
            'id_aula' => 'required|exists:aulas,id_aula',
            'dia_semana' => 'required|in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ];
    }

    private function validarCrucesYCargaDocente(array $data, ?int $ignorarId = null): ?string
    {
        // CU17: evita cruces de horario de aula y docente usando solapamiento de rangos.
        foreach (['id_aula' => 'El aula', 'id_docente' => 'El docente'] as $campo => $etiqueta) {
            $cruce = CargaHoraria::where($campo, $data[$campo])
                ->where('dia_semana', $data['dia_semana'])
                ->when($ignorarId, fn ($query) => $query->where('id_carga_horaria', '!=', $ignorarId))
                ->where('hora_inicio', '<', $data['hora_fin'])
                ->where('hora_fin', '>', $data['hora_inicio'])
                ->exists();

            if ($cruce) {
                return $etiqueta . ' ya tiene una asignacion en ese horario';
            }
        }

        $gruposAsignados = CargaHoraria::where('id_docente', $data['id_docente'])
            ->when($ignorarId, fn ($query) => $query->where('id_carga_horaria', '!=', $ignorarId))
            ->distinct('id_grupo')
            ->count('id_grupo');

        $yaTieneGrupo = CargaHoraria::where('id_docente', $data['id_docente'])
            ->where('id_grupo', $data['id_grupo'])
            ->when($ignorarId, fn ($query) => $query->where('id_carga_horaria', '!=', $ignorarId))
            ->exists();

        // CU17: el docente puede tener como maximo 4 grupos asignados.
        if (!$yaTieneGrupo && $gruposAsignados >= 4) {
            return 'El docente ya tiene 4 grupos asignados';
        }

        return null;
    }

    private function formData(CargaHoraria $carga): array
    {
        return [
            'carga' => $carga,
            'grupos' => Grupo::with('gestion')->where('estado', 'ACTIVO')->get(),
            'materias' => Materia::where('estado', 'ACTIVA')->orderBy('nombre')->get(),
            'docentes' => Docente::where('estado_contratacion', 'ACTIVO')->orderBy('apellidos')->get(),
            'aulas' => Aula::where('estado', 'DISPONIBLE')->orderBy('codigo')->get(),
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Carga Horaria',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
