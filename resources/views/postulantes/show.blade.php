@extends('layouts.app')
<!-- Vista de CU6: Gestionar postulantes. Consulta de datos y relaciones del postulante. -->

@section('titulo', 'Ver Postulante: ' . $postulante->nombres . ' ' . $postulante->apellidos)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-file-person"></i> {{ $postulante->nombres }} {{ $postulante->apellidos }}
    <span class="badge bg-info ms-2">CU6</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">CI</h6>
                        <p><strong>{{ $postulante->ci }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Sexo</h6>
                        <p><strong>{{ $postulante->sexo === 'M' ? 'Masculino' : 'Femenino' }}</strong></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Correo</h6>
                        <p>{{ $postulante->correo ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Fecha de Nacimiento</h6>
                        <p>{{ $postulante->fecha_nacimiento?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Fecha Presentación Formulario</h6>
                    <p>{{ $postulante->fecha_presentacion_formulario?->format('d/m/Y') ?? '-' }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('postulantes.edit', $postulante->id_postulante) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('postulantes.index') }}" class="btn btn-secondary">
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
                @php
                    $color = match($postulante->estado) {
                        'REGISTRADO' => 'warning',
                        'VALIDADO' => 'info',
                        'INSCRITO' => 'success',
                        'RECHAZADO' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <p class="mb-3">
                    <span class="badge bg-{{ $color }} p-3">{{ $postulante->estado }}</span>
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Opciones de Carrera</h5>
            </div>
            <div class="card-body">
                <p><strong>1ª Opción:</strong></p>
                <p>{{ $postulante->carreraOpcionPrimera->nombre ?? '-' }}</p>
                
                <hr>
                
                <p><strong>2ª Opción:</strong></p>
                <p>{{ $postulante->carreraOpcionSegunda->nombre ?? '-' }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Requisitos</h5>
            </div>
            <div class="card-body">
                @if($postulante->requisitos->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($postulante->requisitos as $req)
                            <li class="mb-2">
                                {{ $req->nombre }}
                                <br>
                                <small class="text-muted">
                                    @if($req->pivot->fecha_validacion)
                                        Validado: {{ $req->pivot->fecha_validacion->format('d/m/Y') }}
                                    @else
                                        Pendiente
                                    @endif
                                </small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small">Sin requisitos asignados</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
