<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Usuario;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    /**
     * Listar todas las bitácoras
     */
    public function index()
    {
        try {
            $bitacoras = Bitacora::with('usuario')
                ->orderBy('fecha_hora', 'DESC')
                ->paginate(20);
            
            return view('bitacora.index', [
                'bitacoras' => $bitacoras
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al listar bitácoras: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una entrada de bitácora
     */
    public function show($id)
    {
        try {
            $bitacora = Bitacora::with('usuario')->findOrFail($id);
            
            return view('bitacora.show', [
                'bitacora' => $bitacora
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Bitácora no encontrada: ' . $e->getMessage());
        }
    }

    /**
     * Filtrar bitácoras por criterios
     */
    public function filtrar(Request $request)
    {
        try {
            $query = Bitacora::with('usuario');

            // Filtro por usuario
            if ($request->has('id_usuario') && !empty($request->id_usuario)) {
                $query->where('id_usuario', $request->id_usuario);
            }

            // Filtro por módulo
            if ($request->has('modulo') && !empty($request->modulo)) {
                $query->where('modulo', 'like', '%' . $request->modulo . '%');
            }

            // Filtro por acción
            if ($request->has('accion') && !empty($request->accion)) {
                $query->where('accion', $request->accion);
            }

            // Filtro por rango de fechas
            if ($request->has('fecha_inicio') && !empty($request->fecha_inicio)) {
                $query->whereDate('fecha_hora', '>=', $request->fecha_inicio);
            }

            if ($request->has('fecha_fin') && !empty($request->fecha_fin)) {
                $query->whereDate('fecha_hora', '<=', $request->fecha_fin);
            }

            // Filtro por descripción
            if ($request->has('descripcion') && !empty($request->descripcion)) {
                $query->where('descripcion', 'like', '%' . $request->descripcion . '%');
            }

            $bitacoras = $query->orderBy('fecha_hora', 'DESC')->paginate(20);

            // Obtener datos para los filtros
            $usuarios = Usuario::all();
            $acciones = Bitacora::distinct('accion')->pluck('accion');
            $modulos = Bitacora::distinct('modulo')->pluck('modulo');

            return view('bitacora.filtrar', [
                'bitacoras' => $bitacoras,
                'usuarios' => $usuarios,
                'acciones' => $acciones,
                'modulos' => $modulos,
                'filtrosActivos' => $request->all()
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al filtrar bitácoras: ' . $e->getMessage());
        }
    }

    /**
     * Exportar bitácora (opcional - para futuro)
     */
    public function exportarExcel()
    {
        try {
            // Esta funcionalidad se implementará cuando se agregue la librería de Excel
            return redirect()->back()
                ->with('info', 'Funcionalidad de exportación en desarrollo');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }

    /**
     * Limpiar bitácora antigua (mayor a X días)
     */
    public function limpiarAntiguos(Request $request)
    {
        try {
            $validado = $request->validate([
                'dias' => 'required|integer|min:1|max:365'
            ]);

            $fechaLimite = now()->subDays($validado['dias']);
            
            $eliminadas = Bitacora::where('fecha_hora', '<', $fechaLimite)->delete();

            return redirect()->back()
                ->with('success', 'Se eliminaron ' . $eliminadas . ' registros de bitácora antiguos');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al limpiar bitácora: ' . $e->getMessage());
        }
    }
}
