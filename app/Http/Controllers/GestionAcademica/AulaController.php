<?php

namespace App\Http\Controllers\GestionAcademica;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU16: Gestionar aulas.
 * Valida codigo unico, capacidad mayor a 0 y estado de disponibilidad.
 */
class AulaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $aulas = Aula::when($buscar, function ($query) use ($buscar) {
            $query->where('codigo', 'like', "%{$buscar}%")
                ->orWhere('nombre', 'like', "%{$buscar}%")
                ->orWhere('ubicacion', 'like', "%{$buscar}%");
        })->orderBy('codigo')->paginate(15);

        return view('gestion-academica.aulas.index', compact('aulas', 'buscar'));
    }

    public function create()
    {
        return view('gestion-academica.aulas.create', ['aula' => new Aula()]);
    }

    public function store(Request $request)
    {
        $aula = Aula::create($request->validate($this->rules()));
        $this->registrarBitacora('CREAR', 'Aula ' . $aula->codigo . ' creada');

        return redirect()->route('aulas.index')->with('success', 'Aula registrada correctamente');
    }

    public function edit($id)
    {
        return view('gestion-academica.aulas.edit', ['aula' => Aula::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $aula = Aula::findOrFail($id);
        $aula->update($request->validate($this->rules($id)));
        $this->registrarBitacora('ACTUALIZAR', 'Aula ' . $aula->codigo . ' actualizada');

        return redirect()->route('aulas.index')->with('success', 'Aula actualizada correctamente');
    }

    public function destroy($id)
    {
        $aula = Aula::findOrFail($id);
        if ($aula->cargasHorarias()->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar un aula con carga horaria asignada');
        }

        $codigo = $aula->codigo;
        $aula->delete();
        $this->registrarBitacora('ELIMINAR', 'Aula ' . $codigo . ' eliminada');

        return redirect()->route('aulas.index')->with('success', 'Aula eliminada correctamente');
    }

    private function rules(?int $id = null): array
    {
        return [
            'codigo' => 'required|string|max:20|unique:aulas,codigo,' . $id . ',id_aula',
            'nombre' => 'required|string|max:80',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:100',
            'estado' => 'required|in:DISPONIBLE,NO DISPONIBLE',
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Aulas',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
