<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU15: Gestionar materias.
 * Administra las materias oficiales del CUP y evita duplicados.
 */
class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $materias = Materia::when($buscar, fn ($query) => $query->where('nombre', 'like', "%{$buscar}%"))
            ->orderBy('nombre')
            ->paginate(15);

        return view('gestion-academica.materias.index', compact('materias', 'buscar'));
    }

    public function create()
    {
        return view('gestion-academica.materias.create', ['materia' => new Materia()]);
    }

    public function store(Request $request)
    {
        // CU15: valida campos obligatorios y nombre unico para evitar materias duplicadas.
        $materia = Materia::create($request->validate($this->rules()));
        $this->registrarBitacora('CREAR', 'Materia ' . $materia->nombre . ' creada');

        return redirect()->route('materias.index')->with('success', 'Materia registrada correctamente');
    }

    public function edit($id)
    {
        return view('gestion-academica.materias.edit', ['materia' => Materia::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $materia = Materia::findOrFail($id);
        $materia->update($request->validate($this->rules($id)));
        $this->registrarBitacora('ACTUALIZAR', 'Materia ' . $materia->nombre . ' actualizada');

        return redirect()->route('materias.index')->with('success', 'Materia actualizada correctamente');
    }

    public function destroy($id)
    {
        $materia = Materia::findOrFail($id);
        $nombre = $materia->nombre;

        if ($materia->cargasHorarias()->exists() || $materia->evaluacionesConfig()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar una materia con carga horaria o evaluaciones');
        }

        $materia->delete();
        $this->registrarBitacora('ELIMINAR', 'Materia ' . $nombre . ' eliminada');

        return redirect()->route('materias.index')->with('success', 'Materia eliminada correctamente');
    }

    private function rules(?int $id = null): array
    {
        return [
            'nombre' => 'required|string|max:100|unique:materias,nombre,' . $id . ',id_materia',
            'descripcion' => 'nullable|string|max:200',
            'estado' => 'required|in:ACTIVA,INACTIVA',
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Materias',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
