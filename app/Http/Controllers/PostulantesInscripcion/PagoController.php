<?php

namespace App\Http\Controllers\PostulantesInscripcion;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Pago;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del CU8: Registrar pago de inscripcion.
 * Valida postulante, monto, metodo, estado y registra auditoria en bitacora.
 */
class PagoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // CU8: lista pagos asociados a postulantes y permite buscar por CI, nombre o codigo de transaccion.
        $pagos = Pago::with('postulante')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('codigo_transaccion', 'like', "%{$buscar}%")
                    ->orWhereHas('postulante', function ($subquery) use ($buscar) {
                        $subquery->where('ci', 'like', "%{$buscar}%")
                            ->orWhere('nombres', 'like', "%{$buscar}%")
                            ->orWhere('apellidos', 'like', "%{$buscar}%");
                    });
            })
            ->orderByDesc('id_pago')
            ->paginate(15);

        return view('postulantes-inscripcion.pagos.index', compact('pagos', 'buscar'));
    }

    public function create()
    {
        return view('postulantes-inscripcion.pagos.create', [
            'postulantes' => Postulante::orderBy('apellidos')->orderBy('nombres')->get(),
            'pago' => new Pago(),
        ]);
    }

    public function store(Request $request)
    {
        // CU8: valida que el postulante exista, el monto sea mayor a 0 y el estado sea permitido.
        $validado = $request->validate($this->rules());

        $pago = Pago::create($validado);

        $this->registrarBitacora('CREAR', 'Pago registrado para postulante ID ' . $pago->id_postulante);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente');
    }

    public function show($id)
    {
        return view('postulantes-inscripcion.pagos.show', [
            'pago' => Pago::with('postulante')->findOrFail($id),
        ]);
    }

    public function edit($id)
    {
        return view('postulantes-inscripcion.pagos.edit', [
            'pago' => Pago::findOrFail($id),
            'postulantes' => Postulante::orderBy('apellidos')->orderBy('nombres')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $pago->update($request->validate($this->rules()));

        $this->registrarBitacora('ACTUALIZAR', 'Pago ID ' . $pago->id_pago . ' actualizado');

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente');
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        $this->registrarBitacora('ELIMINAR', 'Pago ID ' . $id . ' eliminado');

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente');
    }

    private function rules(): array
    {
        return [
            'id_postulante' => 'required|exists:postulantes,id_postulante',
            'monto' => 'required|numeric|min:0.01',
            'metodo_pago' => 'required|string|max:30',
            'codigo_transaccion' => 'nullable|string|max:50',
            'estado_pago' => 'required|in:PENDIENTE,PAGADO,RECHAZADO,ANULADO',
        ];
    }

    private function registrarBitacora(string $accion, string $descripcion): void
    {
        Bitacora::create([
            'id_usuario' => Auth::id(),
            'modulo' => 'Pagos de Inscripcion',
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
