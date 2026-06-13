<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') | CUP FICCT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .sidebar {
            background-color: var(--primary-color);
            color: white;
            min-height: 100vh;
            padding: 20px 0;
        }

        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 30px;
        }

        .sidebar a.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .sidebar-package {
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-toggle {
            width: 100%;
            border: 0;
            background: transparent;
            color: rgba(255,255,255,0.78);
            padding: 13px 18px;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0;
        }

        .sidebar-toggle:hover,
        .sidebar-toggle[aria-expanded="true"] {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-toggle .bi-chevron-down {
            transition: transform 0.2s ease;
        }

        .sidebar-toggle[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        .sidebar-submenu a {
            padding-left: 34px;
            font-size: 0.93rem;
        }

        .sidebar-submenu a:hover {
            padding-left: 42px;
        }

        .sidebar-note {
            display: block;
            padding: 9px 20px 9px 34px;
            color: rgba(255,255,255,0.45);
            font-size: 0.82rem;
        }

        .main-content {
            padding: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .table {
            background-color: white;
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
        }

        .page-title {
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: bold;
            border-bottom: 3px solid var(--secondary-color);
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-mortarboard"></i> CUP FICCT
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->nombre_usuario }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="background: none; border: none; cursor: pointer;">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 sidebar">
                    @php
                        $reportesAbierto = request()->routeIs('dashboard') || request()->routeIs('reportes.*');
                        $seguridadAbierto = request()->routeIs('usuarios.*') || request()->routeIs('roles.*') || request()->routeIs('bitacora.*');
                        $postulantesAbierto = request()->routeIs('postulantes.*') || request()->routeIs('requisitos.*') || request()->routeIs('pagos.*') || request()->routeIs('inscripciones.*');
                        $carrerasAbierto = request()->routeIs('carreras.*') || request()->routeIs('grupos.*') || request()->routeIs('grupo-estudiantes.*');
                        $gestionAbierto = request()->routeIs('docentes.*') || request()->routeIs('materias.*') || request()->routeIs('aulas.*') || request()->routeIs('carga-horaria.*') || request()->routeIs('asistencias.*');
                        $evaluacionesAbierto = request()->routeIs('evaluaciones.*') || request()->routeIs('notas.*') || request()->routeIs('resultados.*');
                    @endphp

                    <div class="sidebar-package">
                        <button class="sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#menuReportes" aria-expanded="{{ $reportesAbierto ? 'true' : 'false' }}" aria-controls="menuReportes">
                            <span><i class="bi bi-speedometer2"></i> Reportes y panel administrativo</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse sidebar-submenu {{ $reportesAbierto ? 'show' : '' }}" id="menuReportes">
                            @if(Route::has('dashboard'))
                                <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) active @endif">
                                    <i class="bi bi-house"></i> Dashboard administrativo
                                </a>
                            @endif
                            @if(Route::has('reportes.index'))
                                <a href="{{ route('reportes.index') }}" class="@if(request()->routeIs('reportes.*')) active @endif">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="sidebar-package">
                        <button class="sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#menuSeguridad" aria-expanded="{{ $seguridadAbierto ? 'true' : 'false' }}" aria-controls="menuSeguridad">
                            <span><i class="bi bi-shield-lock"></i> Seguridad y usuarios</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse sidebar-submenu {{ $seguridadAbierto ? 'show' : '' }}" id="menuSeguridad">
                            @if(Route::has('usuarios.index'))
                                <a href="{{ route('usuarios.index') }}" class="@if(request()->routeIs('usuarios.*')) active @endif">
                                    <i class="bi bi-people"></i> Usuarios
                                </a>
                            @endif
                            @if(Route::has('roles.index'))
                                <a href="{{ route('roles.index') }}" class="@if(request()->routeIs('roles.*')) active @endif">
                                    <i class="bi bi-shield-lock"></i> Roles y permisos
                                </a>
                            @endif
                            @if(Route::has('bitacora.index'))
                                <a href="{{ route('bitacora.index') }}" class="@if(request()->routeIs('bitacora.*')) active @endif">
                                    <i class="bi bi-clock-history"></i> Bitacora
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="sidebar-package">
                        <button class="sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#menuPostulantes" aria-expanded="{{ $postulantesAbierto ? 'true' : 'false' }}" aria-controls="menuPostulantes">
                            <span><i class="bi bi-file-person"></i> Postulantes e inscripcion</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse sidebar-submenu {{ $postulantesAbierto ? 'show' : '' }}" id="menuPostulantes">
                            @if(Route::has('postulantes.index'))
                                <a href="{{ route('postulantes.index') }}" class="@if(request()->routeIs('postulantes.*') && !request()->routeIs('postulantes.pre-registro')) active @endif">
                                    <i class="bi bi-file-person"></i> Postulantes
                                </a>
                            @endif
                            @if(Route::has('requisitos.index'))
                                <a href="{{ route('requisitos.index') }}" class="@if(request()->routeIs('requisitos.*')) active @endif">
                                    <i class="bi bi-checklist"></i> Validar requisitos
                                </a>
                            @endif
                            @if(Route::has('postulantes.pre-registro'))
                                <a href="{{ route('postulantes.pre-registro') }}" class="@if(request()->routeIs('postulantes.pre-registro')) active @endif">
                                    <i class="bi bi-person-plus"></i> Registro postulante
                                </a>
                            @endif
                            @if(Route::has('pagos.index'))
                                <a href="{{ route('pagos.index') }}" class="@if(request()->routeIs('pagos.*')) active @endif">
                                    <i class="bi bi-cash-coin"></i> Pagos
                                </a>
                            @endif
                            @if(Route::has('inscripciones.index'))
                                <a href="{{ route('inscripciones.index') }}" class="@if(request()->routeIs('inscripciones.*')) active @endif">
                                    <i class="bi bi-journal-check"></i> Inscripciones
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="sidebar-package">
                        <button class="sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#menuCarreras" aria-expanded="{{ $carrerasAbierto ? 'true' : 'false' }}" aria-controls="menuCarreras">
                            <span><i class="bi bi-mortarboard"></i> Carreras, cupos y grupos</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse sidebar-submenu {{ $carrerasAbierto ? 'show' : '' }}" id="menuCarreras">
                            @if(Route::has('carreras.index'))
                                <a href="{{ route('carreras.index') }}" class="@if(request()->routeIs('carreras.*')) active @endif">
                                    <i class="bi bi-mortarboard"></i> Carreras
                                </a>
                            @endif
                            <span class="sidebar-note">Cupos por carrera: desde detalle</span>
                            @if(Route::has('grupos.index'))
                                <a href="{{ route('grupos.index') }}" class="@if(request()->routeIs('grupos.*')) active @endif">
                                    <i class="bi bi-collection"></i> Grupos
                                </a>
                            @endif
                            @if(Route::has('grupo-estudiantes.index'))
                                <a href="{{ route('grupo-estudiantes.index') }}" class="@if(request()->routeIs('grupo-estudiantes.*')) active @endif">
                                    <i class="bi bi-person-plus"></i> Asignar estudiantes
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="sidebar-package">
                        <button class="sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#menuGestion" aria-expanded="{{ $gestionAbierto ? 'true' : 'false' }}" aria-controls="menuGestion">
                            <span><i class="bi bi-calendar-week"></i> Gestion academica</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse sidebar-submenu {{ $gestionAbierto ? 'show' : '' }}" id="menuGestion">
                            @if(Route::has('docentes.index'))
                                <a href="{{ route('docentes.index') }}" class="@if(request()->routeIs('docentes.*')) active @endif">
                                    <i class="bi bi-person-badge"></i> Docentes
                                </a>
                            @endif
                            @if(Route::has('materias.index'))
                                <a href="{{ route('materias.index') }}" class="@if(request()->routeIs('materias.*')) active @endif">
                                    <i class="bi bi-book"></i> Materias
                                </a>
                            @endif
                            @if(Route::has('aulas.index'))
                                <a href="{{ route('aulas.index') }}" class="@if(request()->routeIs('aulas.*')) active @endif">
                                    <i class="bi bi-door-open"></i> Aulas
                                </a>
                            @endif
                            @if(Route::has('carga-horaria.index'))
                                <a href="{{ route('carga-horaria.index') }}" class="@if(request()->routeIs('carga-horaria.*')) active @endif">
                                    <i class="bi bi-calendar-week"></i> Carga horaria
                                </a>
                            @endif
                            @if(Route::has('asistencias.index'))
                                <a href="{{ route('asistencias.index') }}" class="@if(request()->routeIs('asistencias.*')) active @endif">
                                    <i class="bi bi-clipboard-check"></i> Asistencia
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="sidebar-package">
                        <button class="sidebar-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#menuEvaluaciones" aria-expanded="{{ $evaluacionesAbierto ? 'true' : 'false' }}" aria-controls="menuEvaluaciones">
                            <span><i class="bi bi-award"></i> Evaluaciones y admision</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="collapse sidebar-submenu {{ $evaluacionesAbierto ? 'show' : '' }}" id="menuEvaluaciones">
                            @if(Route::has('evaluaciones.index'))
                                <a href="{{ route('evaluaciones.index') }}" class="@if(request()->routeIs('evaluaciones.*')) active @endif">
                                    <i class="bi bi-list-check"></i> Evaluaciones
                                </a>
                            @endif
                            @if(Route::has('notas.index'))
                                <a href="{{ route('notas.index') }}" class="@if(request()->routeIs('notas.*')) active @endif">
                                    <i class="bi bi-pencil-square"></i> Notas
                                </a>
                            @endif
                            @if(Route::has('resultados.index'))
                                <a href="{{ route('resultados.index') }}" class="@if(request()->routeIs('resultados.*')) active @endif">
                                    <i class="bi bi-award"></i> Resultados y admision
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-10 main-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Errores de Validacion</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('contenido')
                </div>
            </div>
        </div>
    @else
        @yield('contenido')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
