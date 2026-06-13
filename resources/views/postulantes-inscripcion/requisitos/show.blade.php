@extends('layouts.app')
<!-- Vista de CU7: Validar requisitos. Consulta de detalle del requisito. -->

@section('titulo', 'Ver Requisito: ' . $requisito->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-checklist"></i> {{ $requisito->nombre }}
    <span class="badge bg-info ms-2">CU7</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informacion del requisito</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Nombre</h6>
                        <p><strong>{{ $requisito->nombre }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Estado</h6>
                        <p>
                            @if($requisito->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Obligatorio</h6>
                        <p>
                            @if($requisito->obligatorio)
                                <span class="badge bg-danger">Si</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Descripcion</h6>
                    <p>{{ $requisito->descripcion ?? 'Sin descripcion' }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('requisitos.edit', $requisito->id_requisito) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="{{ route('requisitos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Postulantes</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <strong>{{ $requisito->postulantes->count() }}</strong> postulantes tienen este requisito
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
