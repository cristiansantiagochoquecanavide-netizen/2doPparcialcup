@extends('layouts.app')
<!-- Vista de CU10: Gestionar carreras. Consulta de detalle y estadisticas de carrera. -->

@section('titulo', 'Ver Carrera: ' . $carrera->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-mortarboard"></i> {{ $carrera->nombre }}
    <span class="badge bg-info ms-2">CU10</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de la Carrera</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Código</h6>
                        <p><strong>{{ $carrera->codigo }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Estado</h6>
                        <p>
                            @if($carrera->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Nombre Completo</h6>
                    <p><strong>{{ $carrera->nombre }}</strong></p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('carreras.edit', $carrera->id_carrera) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('carreras.asignar-cupo', $carrera->id_carrera) }}" class="btn btn-info">
                        <i class="bi bi-diagram-3"></i> Asignar Cupo
                    </a>
                    <a href="{{ route('carreras.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Estadísticas</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Postulantes:</strong> 
                    <span class="badge bg-secondary">{{ $carrera->postulantes->count() }}</span>
                </p>
                <p>
                    <strong>Grupos:</strong> 
                    <span class="badge bg-info">{{ $carrera->grupos->count() }}</span>
                </p>
                <p>
                    <strong>Docentes:</strong> 
                    <span class="badge bg-primary">{{ $carrera->docentes->count() }}</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
