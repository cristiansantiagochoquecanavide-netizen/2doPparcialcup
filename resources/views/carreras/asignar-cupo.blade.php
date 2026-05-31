@extends('layouts.app')
<!-- Vista de CU10: Gestionar carreras. Formulario para asignar cupos por gestion. -->

@section('titulo', 'Asignar Cupo: ' . $carrera->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-diagram-3"></i> Asignar Cupo - {{ $carrera->nombre }}
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('carreras.guardar-cupo', $carrera->id_carrera) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="id_gestion" class="form-label">
                            <i class="bi bi-calendar-range"></i> Gestión/Año Académico
                        </label>
                        <select class="form-select @error('id_gestion') is-invalid @enderror" id="id_gestion" name="id_gestion" required>
                            <option value="">-- Seleccionar Gestión --</option>
                            @foreach($gestiones as $gestion)
                                <option value="{{ $gestion->id_gestion }}" @selected(old('id_gestion') == $gestion->id_gestion)>
                                    {{ $gestion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_gestion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cupo_maximo" class="form-label">
                            <i class="bi bi-diagram-3"></i> Cupo Máximo
                        </label>
                        <input 
                            type="number" 
                            class="form-control @error('cupo_maximo') is-invalid @enderror" 
                            id="cupo_maximo" 
                            name="cupo_maximo"
                            value="{{ old('cupo_maximo') }}"
                            placeholder="Ej: 50"
                            min="1"
                            required
                        >
                        <small class="text-muted">Cantidad máxima de estudiantes a aceptar</small>
                        @error('cupo_maximo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Asigne el cupo máximo de estudiantes para esta carrera en la gestión seleccionada.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Asignar Cupo
                        </button>
                        <a href="{{ route('carreras.show', $carrera->id_carrera) }}" class="btn btn-secondary">
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
                <h5 class="mb-0">Información</h5>
            </div>
            <div class="card-body">
                <p><strong>Carrera:</strong> {{ $carrera->nombre }}</p>
                <p><strong>Código:</strong> {{ $carrera->codigo }}</p>
                <p class="mb-0"><strong>Estado:</strong> 
                    @if($carrera->estado === 'ACTIVO')
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-danger">Inactivo</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Gestiones Disponibles</h5>
            </div>
            <div class="card-body">
                @if($gestiones->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($gestiones as $gestion)
                            <li class="mb-2">
                                <i class="bi bi-calendar"></i> {{ $gestion->nombre }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted small">No hay gestiones disponibles</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
