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

// ============================================================
// RUTAS PÚBLICAS (Sin autenticación)
// ============================================================

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ============================================================
// RUTAS PROTEGIDAS (Con autenticación)
// ============================================================

Route::middleware('auth')->group(function () {

    // ============================================================
    // AUTENTICACIÓN
    // ============================================================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // ============================================================
    // GESTIÓN DE USUARIOS
    // ============================================================
    Route::resource('usuarios', UsuarioController::class);

    // ============================================================
    // GESTIÓN DE ROLES Y PERMISOS
    // ============================================================
    Route::resource('roles', RolController::class);
    Route::get('/roles/{id}/asignar-permisos', [RolController::class, 'asignarPermisos'])->name('roles.asignar-permisos');
    Route::post('/roles/{id}/guardar-permisos', [RolController::class, 'guardarPermisos'])->name('roles.guardar-permisos');

    // ============================================================
    // BITÁCORA Y AUDITORÍA
    // ============================================================
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
    Route::get('/bitacora-filtrar', [BitacoraController::class, 'filtrar'])->name('bitacora.filtrar');
    Route::post('/bitacora-limpiar', [BitacoraController::class, 'limpiarAntiguos'])->name('bitacora.limpiar');

    // ============================================================
    // GESTIÓN DE POSTULANTES
    // ============================================================
    Route::resource('postulantes', PostulanteController::class);
    Route::post('/postulantes/{id}/cambiar-estado', [PostulanteController::class, 'cambiarEstado'])->name('postulantes.cambiar-estado');

    // ============================================================
    // GESTIÓN DE REQUISITOS
    // ============================================================
    Route::resource('requisitos', RequisitoController::class);
    Route::get('/postulantes/{id}/requisitos-validar', [RequisitoController::class, 'validarPostulante'])->name('requisitos.validar-postulante');
    Route::post('/postulantes/{id}/requisitos-guardar', [RequisitoController::class, 'guardarValidacion'])->name('requisitos.guardar-validacion');

    // ============================================================
    // GESTIÓN DE CARRERAS
    // ============================================================
    Route::resource('carreras', CarreraController::class);
    Route::get('/carreras/{id}/asignar-cupo', [CarreraController::class, 'asignarCupo'])->name('carreras.asignar-cupo');
    Route::post('/carreras/{id}/guardar-cupo', [CarreraController::class, 'guardarCupo'])->name('carreras.guardar-cupo');

    // ============================================================
    // GESTIÓN DE GRUPOS
    // ============================================================
    Route::resource('grupos', GrupoController::class);
    Route::get('/grupos-calcular', [GrupoController::class, 'calculadora'])->name('grupos.calculadora');
    Route::post('/grupos-calcular', [GrupoController::class, 'calcularCantidadGrupos'])->name('grupos.calcular');

    // ============================================================
    // GESTIÓN DE DOCENTES
    // ============================================================
    Route::resource('docentes', DocenteController::class);
    Route::post('/docentes/{id}/cambiar-estado', [DocenteController::class, 'cambiarEstado'])->name('docentes.cambiar-estado');
    Route::get('/docentes-filtrar', [DocenteController::class, 'filtrar'])->name('docentes.filtrar');

});
