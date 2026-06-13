@extends('layouts.app')
<!-- Vista de CU7: Validar requisitos. Permite marcar requisitos presentados por postulante. -->

@section('titulo', 'Validar Requisitos del Postulante')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-check-circle"></i> Validar Requisitos - {{ $postulante->nombres }} {{ $postulante->apellidos }}
    <span class="badge bg-info ms-2">CU7</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('requisitos.guardar-validacion', $postulante->id_postulante) }}" method="POST">
                    @csrf

                    @if($requisitosActivos->count() > 0)
                        <div class="list-group">
                            @foreach($requisitosActivos as $index => $requisito)
                                @php
                                    $requisitoPostulante = $postulante->requisitos->firstWhere('id_requisito', $requisito->id_requisito);
                                    $presentado = (bool) ($requisitoPostulante?->pivot?->presentado ?? false);
                                    $fechaPresentacion = $requisitoPostulante?->pivot?->fecha_presentacion;
                                @endphp
                                <div class="list-group-item">
                                    <input type="hidden" name="requisitos[{{ $index }}][id_requisito]" value="{{ $requisito->id_requisito }}">
                                    <input type="hidden" name="requisitos[{{ $index }}][presentado]" value="0">

                                    <div class="row align-items-center g-2">
                                        <div class="col-md-5">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="requisito_{{ $requisito->id_requisito }}"
                                                    name="requisitos[{{ $index }}][presentado]"
                                                    value="1"
                                                    @checked(old("requisitos.$index.presentado", $presentado))
                                                >
                                                <label class="form-check-label" for="requisito_{{ $requisito->id_requisito }}">
                                                    <strong>{{ $requisito->nombre }}</strong>
                                                    @if($requisito->obligatorio)
                                                        <span class="badge bg-danger">Obligatorio</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input
                                                type="date"
                                                class="form-control form-control-sm @error("requisitos.$index.fecha_presentacion") is-invalid @enderror"
                                                name="requisitos[{{ $index }}][fecha_presentacion]"
                                                value="{{ old("requisitos.$index.fecha_presentacion", $fechaPresentacion ? \Illuminate\Support\Carbon::parse($fechaPresentacion)->format('Y-m-d') : now()->toDateString()) }}"
                                            >
                                            @error("requisitos.$index.fecha_presentacion")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <input
                                                type="text"
                                                class="form-control form-control-sm @error("requisitos.$index.observacion") is-invalid @enderror"
                                                name="requisitos[{{ $index }}][observacion]"
                                                value="{{ old("requisitos.$index.observacion", $requisitoPostulante?->pivot?->observacion) }}"
                                                placeholder="Observacion"
                                                maxlength="255"
                                            >
                                            @error("requisitos.$index.observacion")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No hay requisitos activos disponibles</p>
                    @endif

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Validacion
                        </button>
                        <a href="{{ route('postulantes.show', $postulante->id_postulante) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informacion del postulante</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ $postulante->nombres }} {{ $postulante->apellidos }}</strong></p>
                <p><small class="text-muted">CI: {{ $postulante->ci }}</small></p>
                <p><small class="text-muted">Estado:
                    @php
                        $color = match($postulante->estado) {
                            'REGISTRADO' => 'warning',
                            'VALIDADO' => 'info',
                            'INSCRITO' => 'success',
                            'RECHAZADO' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $postulante->estado }}</span>
                </small></p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Resumen</h5>
            </div>
            <div class="card-body">
                <p><strong>Total requisitos:</strong> {{ $requisitosActivos->count() }}</p>
                <p><strong>Obligatorios:</strong> {{ $requisitosActivos->where('obligatorio', true)->count() }}</p>
                <p><strong>Opcionales:</strong> {{ $requisitosActivos->where('obligatorio', false)->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
