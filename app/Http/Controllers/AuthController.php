<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Procesar login
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
                    ->with('error', 'Credenciales inválidas');
            }

            if ($usuario->estado !== 'ACTIVO') {
                return redirect()->back()
                    ->with('error', 'Usuario inactivo');
            }

            Auth::login($usuario);
            
            // Registrar en bitácora
            Bitacora::create([
                'id_usuario' => $usuario->id_usuario,
                'modulo' => 'Autenticación',
                'accion' => 'LOGIN',
                'descripcion' => 'Usuario ' . $usuario->nombre_usuario . ' inició sesión'
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenido ' . $usuario->nombre_usuario);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar login: ' . $e->getMessage());
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        try {
            $usuario = Auth::user();
            
            // Registrar en bitácora
            if ($usuario) {
                Bitacora::create([
                    'id_usuario' => $usuario->id_usuario,
                    'modulo' => 'Autenticación',
                    'accion' => 'LOGOUT',
                    'descripcion' => 'Usuario ' . $usuario->nombre_usuario . ' cerró sesión'
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Sesión cerrada correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cerrar sesión: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar dashboard
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
