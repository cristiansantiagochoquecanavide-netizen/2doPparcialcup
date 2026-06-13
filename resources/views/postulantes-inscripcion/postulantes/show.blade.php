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
                <h5 class="mb-0">Datos del Postulante</h5>
            </div>
            <div class="card-body">
                {{-- Se muestran todos los datos personales obligatorios del registro. --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <h6 class="text-muted">CI</h6>
                        <p><strong>{{ $postulante->ci }}</strong></p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Nombres</h6>
                        <p>{{ $postulante->nombres }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Apellidos</h6>
                        <p>{{ $postulante->apellidos }}</p>
                    </div>
                </div>

                {{-- Datos administrativos principales del postulante. --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <h6 class="text-muted">Fecha de nacimiento</h6>
                        <p>{{ $postulante->fecha_nacimiento?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Sexo</h6>
                        <p>{{ $postulante->sexo === 'M' ? 'Masculino' : 'Femenino' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Estado</h6>
                        @php
                            $color = match($postulante->estado) {
                                'PENDIENTE_VALIDACION' => 'secondary',
                                'REGISTRADO' => 'warning',
                                'VALIDADO' => 'info',
                                'INSCRITO' => 'success',
                                'RECHAZADO' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <p><span class="badge bg-{{ $color }}">{{ $postulante->estado }}</span></p>
                    </div>
                </div>

                {{-- Datos de contacto registrados en crear/editar. --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Correo electronico</h6>
                        <p>{{ $postulante->correo }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Telefono</h6>
                        <p>{{ $postulante->telefono ?? '-' }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Direccion</h6>
                    <p>{{ $postulante->direccion ?? '-' }}</p>
                </div>

                {{-- Procedencia academica del postulante. --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Colegio de procedencia</h6>
                        <p>{{ $postulante->colegio_procedencia ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Ciudad</h6>
                        <p>{{ $postulante->ciudad ?? '-' }}</p>
                    </div>
                </div>

                {{-- Opciones de carrera relacionadas con la tabla carreras. --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Carrera de primera opcion</h6>
                        <p>{{ $postulante->carreraOpcionPrimera->nombre ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Carrera de segunda opcion</h6>
                        <p>{{ $postulante->carreraOpcionSegunda->nombre ?? '-' }}</p>
                    </div>
                </div>

                {{-- Documentacion y observaciones adicionales. --}}
                <div class="mb-3">
                    <h6 class="text-muted">Titulo de bachiller</h6>
                    <p>{{ $postulante->titulo_bachiller ?? '-' }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Otros requisitos</h6>
                    <p>{{ $postulante->otros_requisitos ?? '-' }}</p>
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
                <h5 class="mb-0">Requisitos validados</h5>
            </div>
            <div class="card-body">
                {{-- Lista los requisitos vinculados al postulante desde la tabla pivote. --}}
                @if($postulante->requisitos->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($postulante->requisitos as $req)
                            <li class="mb-2">
                                {{ $req->nombre }}
                                <br>
                                <small class="text-muted">
                                    {{ $req->pivot->presentado ? 'Presentado' : 'Pendiente' }}
                                    @if($req->pivot->fecha_presentacion)
                                        - {{ \Illuminate\Support\Carbon::parse($req->pivot->fecha_presentacion)->format('d/m/Y') }}
                                    @endif
                                </small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small mb-0">Sin requisitos asignados</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
