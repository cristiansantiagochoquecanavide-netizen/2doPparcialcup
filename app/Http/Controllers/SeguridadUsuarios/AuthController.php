<?php

namespace App\Http\Controllers\SeguridadUsuarios;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        return view('seguridad-usuarios.auth.login');
    }

    /**
     * Procesa credenciales e inicia la sesion del usuario.
     * Corresponde al flujo IniciarSesion() del CU1.
     */
    public function login(Request $request)
    {
        try {
            $credenciales = $request->validate([
                'correo' => 'required|email',
                'password' => 'required|string'
            ]);

            $usuario = Usuario::where('correo', $credenciales['correo'])->first();

            if (!$usuario || !Hash::check($credenciales['password'], $usuario->password_hash)) {
                return redirect()->back()
                    ->withInput($request->only('correo'))
                    ->with('error', 'Credenciales inválidas');
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
     * Muestra el formulario para recuperación de contraseña.
     */
    public function showResetForm()
    {
        return view('seguridad-usuarios.auth.reset-password');
    }

    /**
     * Procesa la solicitud de recuperación de contraseña.
     */
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'correo' => 'required|email|exists:usuarios,correo'
            ], [
                'correo.exists' => 'No encontramos una cuenta con este correo.'
            ]);

            $usuario = Usuario::where('correo', $request->correo)->first();

            // Generar token
            $token = Str::random(64);
            $resetUrl = route('password.reset.form', $token);

            // Guardar en base de datos
            DB::table('password_resets')->where('email', $usuario->correo)->delete();
            DB::table('password_resets')->insert([
                'email' => $usuario->correo,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            // Enviar correo
            // Usa la vista movida al paquete Seguridad y usuarios.
            Mail::send('seguridad-usuarios.auth.email-reset', [
                'usuario' => $usuario,
                'resetUrl' => $resetUrl
            ], function($message) use ($usuario) {
                $message->to($usuario->correo)
                    ->subject('Recuperación de Contraseña - CUP FICCT');
            });

            return redirect()->back()
                ->with('success', 'Se ha enviado un enlace de recuperación a tu correo electrónico.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para establecer nueva contraseña.
     */
    public function showNewPasswordForm($token)
    {
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->first();

        if (!$passwordReset) {
            return redirect()->route('password.reset')
                ->with('error', 'El enlace de recuperación es inválido o ha expirado.');
        }

        return view('seguridad-usuarios.auth.new-password', compact('token', 'passwordReset'));
    }

    /**
     * Procesa el cambio de contraseña.
     */
    public function resetPassword(Request $request, $token)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);

            $passwordReset = DB::table('password_resets')
                ->where('token', $token)
                ->where('created_at', '>', Carbon::now()->subHours(24))
                ->first();

            if (!$passwordReset) {
                return redirect()->route('password.reset')
                    ->with('error', 'El enlace de recuperación es inválido o ha expirado.');
            }

            $usuario = Usuario::where('correo', $passwordReset->email)->first();
            $usuario->update([
                'password_hash' => Hash::make($request->password)
            ]);

            DB::table('password_resets')->where('email', $usuario->correo)->delete();

            return redirect()->route('login')
                ->with('success', 'Tu contraseña ha sido restablecida. Por favor, inicia sesión.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar la contraseña: ' . $e->getMessage());
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

}
