@extends('layouts.app')
<!-- Vista de CU12: Gestionar grupos. Consulta de detalle, ocupacion y estudiantes. -->

@section('titulo', 'Ver Grupo: ' . $grupo->codigo_grupo)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-people"></i> {{ $grupo->codigo_grupo }}
    <span class="badge bg-info ms-2">CU12</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informacion del grupo</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Codigo</h6>
                        <p><strong>{{ $grupo->codigo_grupo }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Gestion</h6>
                        <p><strong>{{ $grupo->gestion->nombre ?? '-' }}</strong></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Cupo maximo</h6>
                        <p><strong>{{ $grupo->cupo_maximo }}</strong> estudiantes</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Estado</h6>
                        <p>
                            @if($grupo->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('grupos.edit', $grupo->id_grupo) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ocupacion</h5>
            </div>
            <div class="card-body">
                @php
                    $colorOcupacion = $ocupacion['porcentaje'] >= 80 ? 'danger' : ($ocupacion['porcentaje'] >= 50 ? 'warning' : 'success');
                @endphp
                <div class="progress" style="height: 30px;">
                    <div
                        class="progress-bar bg-{{ $colorOcupacion }}"
                        role="progressbar"
                        style="width: {{ min($ocupacion['porcentaje'], 100) }}%;"
                        aria-valuenow="{{ $ocupacion['porcentaje'] }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    >
                        {{ $ocupacion['porcentaje'] }}%
                    </div>
                </div>
                <p class="mt-3 mb-0">
                    <strong>{{ $ocupacion['estudiantes'] }} / {{ $ocupacion['cupo_maximo'] }}</strong> estudiantes
                </p>
                <p class="text-muted small mb-0">{{ $ocupacion['disponibles'] }} cupos disponibles</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Estudiantes</h5>
            </div>
            <div class="card-body">
                @if($grupo->estudiantesGrupo->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($grupo->estudiantesGrupo->take(5) as $asignacion)
                            <li class="mb-2">
                                <i class="bi bi-person"></i>
                                {{ $asignacion->inscripcion->postulante->nombres ?? 'Sin nombre' }}
                                {{ $asignacion->inscripcion->postulante->apellidos ?? '' }}
                            </li>
                        @endforeach
                        @if($grupo->estudiantesGrupo->count() > 5)
                            <li class="text-muted small">
                                ... y {{ $grupo->estudiantesGrupo->count() - 5 }} mas
                            </li>
                        @endif
                    </ul>
                @else
                    <p class="text-muted small">Sin estudiantes asignados</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
