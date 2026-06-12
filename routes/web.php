<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\RequisitoController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\ReporteController;

// CU1 y CU2: Autenticacion del usuario.
// Rutas publicas para iniciar sesion y redirigir al formulario de acceso.
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rutas para recuperación de contraseña
Route::get('/password/reset', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showNewPasswordForm'])->name('password.reset.form');
Route::post('/password/reset/{token}', [AuthController::class, 'resetPassword'])->name('password.reset.post');

// CU25: pre-registro publico de postulantes, disponible sin iniciar sesion.
Route::get('/pre-registro', [PostulanteController::class, 'preRegistro'])->name('postulantes.pre-registro');
Route::post('/pre-registro', [PostulanteController::class, 'guardarPreRegistro'])->name('postulantes.guardar-pre-registro');

// Rutas protegidas por autenticacion.
// Agrupa los casos de uso disponibles despues de iniciar sesion.
Route::middleware('auth')->group(function () {

    // CU1 y CU2: Autenticacion del usuario.
    // Rutas para cerrar sesion y acceder al dashboard autenticado.
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // CU3: Gestionar usuarios.
    // Rutas para crear, listar, editar, consultar y eliminar usuarios.
    Route::resource('usuarios', UsuarioController::class);

    // CU4: Gestionar roles y asignar permisos.
    // Rutas para administrar roles y vincular permisos del sistema.
    Route::resource('roles', RolController::class);
    Route::get('/roles/{id}/asignar-permisos', [RolController::class, 'asignarPermisos'])->name('roles.asignar-permisos');
    Route::post('/roles/{id}/guardar-permisos', [RolController::class, 'guardarPermisos'])->name('roles.guardar-permisos');

    // CU5: Ver bitacora de acciones y auditoria.
    // Rutas para consultar, filtrar y limpiar registros auditados.
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
    Route::get('/bitacora-filtrar', [BitacoraController::class, 'filtrar'])->name('bitacora.filtrar');
    Route::post('/bitacora-limpiar', [BitacoraController::class, 'limpiarAntiguos'])->name('bitacora.limpiar');

    // CU6: Gestionar postulantes.
    // Rutas para registrar, consultar, editar, eliminar y cambiar estado.
    Route::resource('postulantes', PostulanteController::class);
    Route::post('/postulantes/{id}/cambiar-estado', [PostulanteController::class, 'cambiarEstado'])->name('postulantes.cambiar-estado');

    // CU7: Validar requisitos del postulante.
    // Rutas para administrar requisitos y validar documentos por postulante.
    Route::resource('requisitos', RequisitoController::class);
    Route::get('/postulantes/{id}/requisitos-validar', [RequisitoController::class, 'validarPostulante'])->name('requisitos.validar-postulante');
    Route::post('/postulantes/{id}/requisitos-guardar', [RequisitoController::class, 'guardarValidacion'])->name('requisitos.guardar-validacion');

    // CU10: Gestionar carreras.
    // Rutas para administrar carreras y asignar cupos por gestion.
    Route::resource('carreras', CarreraController::class);
    Route::get('/carreras/{id}/asignar-cupo', [CarreraController::class, 'asignarCupo'])->name('carreras.asignar-cupo');
    Route::post('/carreras/{id}/guardar-cupo', [CarreraController::class, 'guardarCupo'])->name('carreras.guardar-cupo');

    // CU12: Gestionar grupos.
    // Rutas para administrar grupos y calcular distribucion de cupos.
    Route::resource('grupos', GrupoController::class);
    Route::get('/grupos-calcular', [GrupoController::class, 'calculadora'])->name('grupos.calculadora');
    Route::post('/grupos-calcular', [GrupoController::class, 'calcularCantidadGrupos'])->name('grupos.calcular');

    // CU14: Gestionar docentes.
    // Rutas para administrar docentes, filtrar y cambiar estado contractual.
    Route::resource('docentes', DocenteController::class);
    Route::post('/docentes/{id}/cambiar-estado', [DocenteController::class, 'cambiarEstado'])->name('docentes.cambiar-estado');
    Route::get('/docentes-filtrar', [DocenteController::class, 'filtrar'])->name('docentes.filtrar');

    // CU23: reportes administrativos con filtros y registro en bitacora.
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

});
