<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeguridadUsuarios\AuthController;
use App\Http\Controllers\SeguridadUsuarios\UsuarioController;
use App\Http\Controllers\SeguridadUsuarios\RolController;
use App\Http\Controllers\SeguridadUsuarios\BitacoraController;
use App\Http\Controllers\PostulantesInscripcion\PostulanteController;
use App\Http\Controllers\PostulantesInscripcion\RequisitoController;
use App\Http\Controllers\PostulantesInscripcion\PagoController;
use App\Http\Controllers\PostulantesInscripcion\InscripcionController;
use App\Http\Controllers\CarrerasCuposGrupos\CarreraController;
use App\Http\Controllers\CarrerasCuposGrupos\GrupoController;
use App\Http\Controllers\CarrerasCuposGrupos\GrupoEstudianteController;
use App\Http\Controllers\GestionAcademica\DocenteController;
use App\Http\Controllers\GestionAcademica\MateriaController;
use App\Http\Controllers\GestionAcademica\AulaController;
use App\Http\Controllers\GestionAcademica\CargaHorariaController;
use App\Http\Controllers\GestionAcademica\AsistenciaController;
use App\Http\Controllers\EvaluacionesAdmision\EvaluacionController;
use App\Http\Controllers\EvaluacionesAdmision\NotaController;
use App\Http\Controllers\EvaluacionesAdmision\ResultadoAdmisionController;
use App\Http\Controllers\ReportesPanel\ReporteController;
use App\Http\Controllers\ReportesPanel\DashboardController;

// Paquete Seguridad y usuarios: rutas publicas de autenticacion.
Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/password/reset', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showNewPasswordForm'])->name('password.reset.form');
Route::post('/password/reset/{token}', [AuthController::class, 'resetPassword'])->name('password.reset.post');

// Paquete Postulantes e inscripcion: CU25 publico.
Route::get('/pre-registro', [PostulanteController::class, 'preRegistro'])->name('postulantes.pre-registro');
Route::post('/pre-registro', [PostulanteController::class, 'guardarPreRegistro'])->name('postulantes.guardar-pre-registro');

// Rutas protegidas por autenticacion. Si luego se agrega middleware de permisos, debe conservar estos nombres.
Route::middleware('auth')->group(function () {

    // Paquete Seguridad y usuarios.
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('roles', RolController::class);
    Route::get('/roles/{id}/asignar-permisos', [RolController::class, 'asignarPermisos'])->name('roles.asignar-permisos');
    Route::post('/roles/{id}/guardar-permisos', [RolController::class, 'guardarPermisos'])->name('roles.guardar-permisos');
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
    Route::get('/bitacora/{id}', [BitacoraController::class, 'show'])->name('bitacora.show');
    Route::get('/bitacora-filtrar', [BitacoraController::class, 'filtrar'])->name('bitacora.filtrar');
    Route::post('/bitacora-limpiar', [BitacoraController::class, 'limpiarAntiguos'])->name('bitacora.limpiar');

    // Paquete Postulantes e inscripcion.
    Route::resource('postulantes', PostulanteController::class);
    Route::post('/postulantes/{id}/cambiar-estado', [PostulanteController::class, 'cambiarEstado'])->name('postulantes.cambiar-estado');
    Route::resource('requisitos', RequisitoController::class);
    Route::get('/postulantes/{id}/requisitos-validar', [RequisitoController::class, 'validarPostulante'])->name('requisitos.validar-postulante');
    Route::post('/postulantes/{id}/requisitos-guardar', [RequisitoController::class, 'guardarValidacion'])->name('requisitos.guardar-validacion');
    Route::resource('pagos', PagoController::class);
    Route::resource('inscripciones', InscripcionController::class)->except(['edit', 'update']);

    // Paquete Carreras, cupos y grupos.
    Route::resource('carreras', CarreraController::class);
    Route::get('/carreras/{id}/asignar-cupo', [CarreraController::class, 'asignarCupo'])->name('carreras.asignar-cupo');
    Route::post('/carreras/{id}/guardar-cupo', [CarreraController::class, 'guardarCupo'])->name('carreras.guardar-cupo');
    Route::resource('grupos', GrupoController::class);
    Route::get('/grupos-calcular', [GrupoController::class, 'calculadora'])->name('grupos.calculadora');
    Route::post('/grupos-calcular', [GrupoController::class, 'calcularCantidadGrupos'])->name('grupos.calcular');
    Route::resource('grupo-estudiantes', GrupoEstudianteController::class)->only(['index', 'create', 'store', 'destroy']);

    // Paquete Gestion academica.
    Route::resource('docentes', DocenteController::class);
    Route::post('/docentes/{id}/cambiar-estado', [DocenteController::class, 'cambiarEstado'])->name('docentes.cambiar-estado');
    Route::get('/docentes-filtrar', [DocenteController::class, 'filtrar'])->name('docentes.filtrar');
    Route::resource('materias', MateriaController::class)->except(['show']);
    Route::resource('aulas', AulaController::class)->except(['show']);
    Route::resource('carga-horaria', CargaHorariaController::class)->except(['show']);
    Route::resource('asistencias', AsistenciaController::class)->only(['index', 'create', 'store', 'show']);

    // Paquete Evaluaciones y admision.
    Route::resource('evaluaciones', EvaluacionController::class)->except(['show']);
    Route::resource('notas', NotaController::class)->except(['show', 'destroy']);
    Route::get('/resultados', [ResultadoAdmisionController::class, 'index'])->name('resultados.index');
    Route::post('/resultados/{inscripcion}/calcular', [ResultadoAdmisionController::class, 'calcular'])->name('resultados.calcular');
    Route::post('/resultados/{resultado}/asignar-carrera', [ResultadoAdmisionController::class, 'asignarCarrera'])->name('resultados.asignar-carrera');

    // Paquete Reportes y panel administrativo.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});
