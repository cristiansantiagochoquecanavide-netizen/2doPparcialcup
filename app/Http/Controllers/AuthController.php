<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador de CU1 y CU2: Iniciar sesion y cerrar sesion.
 * Valida credenciales, administra la sesion autenticada y registra auditoria.
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesion.
     * Corresponde al flujo MostrarLogin() del CU1.
     */
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa credenciales e inicia la sesion del usuario.
     * Corresponde al flujo IniciarSesion() del CU1.
     */
    public function login(Request $request)
    {
        try {
            $credenciales = $request->validate([
                'nombre_usuario' => 'required|string',
                'password' => 'required|string'
            ]);

            $usuario = Usuario::where('nombre_usuario', $credenciales['nombre_usuario'])->first();

            if (!$usuario || !Hash::check($credenciales['password'], $usuario->password_hash)) {
                return redirect()->back()
                    ->withInput($request->only('nombre_usuario'))
                    ->with('error', 'Credenciales invalidas');
            }

            if ($usuario->estado !== 'ACTIVO') {
                return redirect()->back()
                    ->with('error', 'Usuario inactivo');
            }

            Auth::login($usuario);

            // Registra en bitacora el inicio de sesion, correspondiente al CU1.
            Bitacora::create([
                'id_usuario' => $usuario->id_usuario,
                'modulo' => 'Autenticacion',
                'accion' => 'LOGIN',
                'descripcion' => 'Usuario ' . $usuario->nombre_usuario . ' inicio sesion'
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenido ' . $usuario->nombre_usuario);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar login: ' . $e->getMessage());
        }
    }

    /**
     * Cierra la sesion activa e invalida el token de sesion.
     * Corresponde al flujo CerrarSesion() del CU2.
     */
    public function logout(Request $request)
    {
        try {
            $usuario = Auth::user();

            if ($usuario) {
                // Registra en bitacora el cierre de sesion, correspondiente al CU2.
                Bitacora::create([
                    'id_usuario' => $usuario->id_usuario,
                    'modulo' => 'Autenticacion',
                    'accion' => 'LOGOUT',
                    'descripcion' => 'Usuario ' . $usuario->nombre_usuario . ' cerro sesion'
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Sesion cerrada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cerrar sesion: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el panel principal del usuario autenticado.
     * Corresponde al flujo AccederDashboard() del CU1.
     */
    public function dashboard()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        return view('dashboard', [
            'usuario' => $usuario,
            'rol' => $usuario->rol
        ]);
    }
}
