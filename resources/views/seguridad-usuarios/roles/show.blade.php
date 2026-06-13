@extends('layouts.app')
<!-- Vista de CU4: Gestionar roles. Consulta de rol, permisos y usuarios asociados. -->

@section('titulo', 'Ver Rol: ' . $rol->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-shield-lock"></i> {{ $rol->nombre }}
    <span class="badge bg-info ms-2">CU4</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Rol</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Nombre</h6>
                        <p><strong>{{ $rol->nombre }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Estado</h6>
                        <p>
                            @if($rol->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Descripción</h6>
                    <p>{{ $rol->descripcion ?? 'Sin descripción' }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('roles.edit', $rol->id_rol) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('roles.asignar-permisos', $rol->id_rol) }}" class="btn btn-info">
                        <i class="bi bi-key"></i> Asignar Permisos
                    </a>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Permisos</h5>
            </div>
            <div class="card-body">
                @if($rol->permisos->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($rol->permisos as $permiso)
                            <li class="mb-2">
                                <span class="badge bg-primary">{{ $permiso->nombre }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small">Sin permisos asignados</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Usuarios</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <strong>{{ $rol->usuarios->count() }}</strong> usuarios tienen este rol
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
