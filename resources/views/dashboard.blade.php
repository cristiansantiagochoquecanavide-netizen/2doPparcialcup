@extends('layouts.app')
<!-- Vista de CU1: Dashboard. Muestra accesos a los modulos despues de iniciar sesion. -->

@section('titulo', 'Dashboard')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-house"></i> Dashboard
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-check"></i> Bienvenido, {{ Auth::user()->nombre_usuario }}
                </h5>
                <p class="card-text text-muted">
                    Rol: <strong>{{ Auth::user()->rol->nombre ?? 'Sin rol' }}</strong>
                </p>
                <p class="card-text">
                    Último acceso: <strong>{{ Auth::user()->fecha_creacion->format('d/m/Y H:i') ?? 'N/A' }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-people" style="font-size: 2.5rem; color: #3498db;"></i>
                <h6 class="card-title mt-3">Usuarios</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-primary">Ver</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-shield-lock" style="font-size: 2.5rem; color: #27ae60;"></i>
                <h6 class="card-title mt-3">Roles</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-success">Ver</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-file-person" style="font-size: 2.5rem; color: #e74c3c;"></i>
                <h6 class="card-title mt-3">Postulantes</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('postulantes.index') }}" class="btn btn-sm btn-danger">Ver</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-mortarboard" style="font-size: 2.5rem; color: #f39c12;"></i>
                <h6 class="card-title mt-3">Carreras</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('carreras.index') }}" class="btn btn-sm btn-warning">Ver</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-collection" style="font-size: 2.5rem; color: #9b59b6;"></i>
                <h6 class="card-title mt-3">Grupos</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('grupos.index') }}" class="btn btn-sm btn-primary">Ver</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-person-badge" style="font-size: 2.5rem; color: #16a085;"></i>
                <h6 class="card-title mt-3">Docentes</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('docentes.index') }}" class="btn btn-sm btn-info">Ver</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-checklist" style="font-size: 2.5rem; color: #d35400;"></i>
                <h6 class="card-title mt-3">Requisitos</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('requisitos.index') }}" class="btn btn-sm btn-warning">Ver</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-clock-history" style="font-size: 2.5rem; color: #34495e;"></i>
                <h6 class="card-title mt-3">Bitácora</h6>
                <p class="card-text" style="font-size: 1.5rem; font-weight: bold;">0</p>
                <a href="{{ route('bitacora.index') }}" class="btn btn-sm btn-secondary">Ver</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i> Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Versión:</strong> 1.0.0 (Ciclo 1)</p>
                        <p><strong>Base de Datos:</strong> PostgreSQL - cupficct1</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Última Actualización:</strong> {{ date('d/m/Y H:i') }}</p>
                        <p><strong>Servidor:</strong> Laravel 11</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
