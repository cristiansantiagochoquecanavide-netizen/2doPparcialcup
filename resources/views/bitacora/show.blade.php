@extends('layouts.app')
<!-- Vista de CU5: Ver bitacora. Consulta el detalle de una accion auditada. -->

@section('titulo', 'Ver Bitácora')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-file-text"></i> Registro de Bitácora
    <span class="badge bg-info ms-2">CU5</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detalles</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Fecha/Hora</h6>
                        <p><strong>{{ $bitacora->fecha_hora->format('d/m/Y H:i:s') }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Usuario</h6>
                        <p><strong>{{ $bitacora->usuario->nombre_usuario ?? '-' }}</strong></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Módulo</h6>
                        <p>
                            <span class="badge bg-secondary">{{ $bitacora->modulo }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Acción</h6>
                        <p>
                            @php
                                $color = match($bitacora->accion) {
                                    'CREAR' => 'success',
                                    'ACTUALIZAR' => 'warning',
                                    'ELIMINAR' => 'danger',
                                    'LOGIN' => 'primary',
                                    'LOGOUT' => 'secondary',
                                    default => 'info'
                                };
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $bitacora->accion }}</span>
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Descripción</h6>
                    <p>{{ $bitacora->descripcion }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('bitacora.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
