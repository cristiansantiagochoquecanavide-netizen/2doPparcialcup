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
                                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
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
                    <h6 class="text-uppercase text-muted ps-3 mt-3">Menú Principal</h6>
                    <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) active @endif">
                        <i class="bi bi-house"></i> Dashboard
                    </a>

                    <h6 class="text-uppercase text-muted ps-3 mt-4">Gestión</h6>
                    <a href="{{ route('usuarios.index') }}" class="@if(request()->routeIs('usuarios.*')) active @endif">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <a href="{{ route('roles.index') }}" class="@if(request()->routeIs('roles.*')) active @endif">
                        <i class="bi bi-shield-lock"></i> Roles
                    </a>
                    <a href="{{ route('postulantes.index') }}" class="@if(request()->routeIs('postulantes.*')) active @endif">
                        <i class="bi bi-file-person"></i> Postulantes
                    </a>
                    <a href="{{ route('requisitos.index') }}" class="@if(request()->routeIs('requisitos.*')) active @endif">
                        <i class="bi bi-checklist"></i> Requisitos
                    </a>

                    <h6 class="text-uppercase text-muted ps-3 mt-4">Académico</h6>
                    <a href="{{ route('carreras.index') }}" class="@if(request()->routeIs('carreras.*')) active @endif">
                        <i class="bi bi-mortarboard"></i> Carreras
                    </a>
                    <a href="{{ route('grupos.index') }}" class="@if(request()->routeIs('grupos.*')) active @endif">
                        <i class="bi bi-collection"></i> Grupos
                    </a>
                    <a href="{{ route('docentes.index') }}" class="@if(request()->routeIs('docentes.*')) active @endif">
                        <i class="bi bi-person-badge"></i> Docentes
                    </a>

                    <h6 class="text-uppercase text-muted ps-3 mt-4">Auditoría</h6>
                    <a href="{{ route('reportes.index') }}" class="@if(request()->routeIs('reportes.*')) active @endif">
                        <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                    </a>
                    <a href="{{ route('bitacora.index') }}" class="@if(request()->routeIs('bitacora.*')) active @endif">
                        <i class="bi bi-clock-history"></i> Bitácora
                    </a>
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
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Errores de Validación</h5>
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
