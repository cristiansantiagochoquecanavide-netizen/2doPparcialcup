@extends('layouts.app')
<!-- Vista de CU3: Gestionar usuarios. Consulta de detalle del usuario. -->

@section('titulo', 'Ver Usuario: ' . $usuario->nombre_usuario)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-person"></i> {{ $usuario->nombre_usuario }}
    <span class="badge bg-info ms-2">CU3</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Usuario</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Usuario</h6>
                        <p><strong>{{ $usuario->nombre_usuario }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Correo</h6>
                        <p><strong>{{ $usuario->correo }}</strong></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Rol</h6>
                        <p>
                            <span class="badge bg-info">{{ $usuario->rol->nombre ?? 'Sin rol' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Estado</h6>
                        <p>
                            @if($usuario->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6 class="text-muted">Fecha de Creación</h6>
                        <p><strong>{{ $usuario->fecha_creacion->format('d/m/Y H:i:s') }}</strong></p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actividad</h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted">Bitácoras</h6>
                @if($usuario->bitacoras->count() > 0)
                    <p class="mb-0">
                        <strong>{{ $usuario->bitacoras->count() }}</strong> registros
                    </p>
                    <small class="text-muted">
                        Última: {{ $usuario->bitacoras->first()->fecha_hora->format('d/m/Y H:i') }}
                    </small>
                @else
                    <p class="text-muted small">Sin actividad registrada</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
