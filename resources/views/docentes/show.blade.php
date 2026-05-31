@extends('layouts.app')

@section('titulo', 'Ver Docente: ' . $docente->nombres . ' ' . $docente->apellidos)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-person-video2"></i> {{ $docente->nombres }} {{ $docente->apellidos }}
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informacion personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">CI</h6>
                        <p><strong>{{ $docente->ci }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Correo</h6>
                        <p>{{ $docente->correo ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Telefono</h6>
                        <p>{{ $docente->telefono ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Area profesional</h6>
                        <p>{{ $docente->profesional_area ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Maestria</h6>
                        <p>
                            @if($docente->tiene_maestria)
                                <span class="badge bg-success">Si</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Diplomado en educacion superior</h6>
                        <p>
                            @if($docente->tiene_diplomado_educacion_superior)
                                <span class="badge bg-success">Si</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('docentes.edit', $docente->id_docente) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('docentes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estado</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Contratacion:</strong>
                    @php
                        $colorEstado = match($docente->estado_contratacion) {
                            'ACTIVO' => 'success',
                            'INACTIVO' => 'danger',
                            'LICENCIA' => 'warning',
                            'JUBILADO' => 'info',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $colorEstado }}">{{ $docente->estado_contratacion }}</span>
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Cargas Horarias</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <strong>{{ $docente->cargasHorarias->count() }}</strong> cargas horarias asignadas
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
