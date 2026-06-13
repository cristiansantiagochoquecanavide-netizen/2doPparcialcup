@extends('layouts.app')
<!-- Vista de CU10: Gestionar carreras. Formulario para actualizar carreras. -->

@section('titulo', 'Editar Carrera: ' . $carrera->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Carrera
    <span class="badge bg-info ms-2">CU10</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('carreras.update', $carrera->id_carrera) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="codigo" class="form-label">
                            <i class="bi bi-code"></i> Código
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('codigo') is-invalid @enderror" 
                            id="codigo" 
                            name="codigo"
                            value="{{ old('codigo', $carrera->codigo) }}"
                            required
                        >
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-mortarboard"></i> Nombre de la Carrera
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" 
                            name="nombre"
                            value="{{ old('nombre', $carrera->nombre) }}"
                            required
                        >
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">
                            <i class="bi bi-toggle-on"></i> Estado
                        </label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="ACTIVO" @selected(old('estado', $carrera->estado) === 'ACTIVO')>Activo</option>
                            <option value="INACTIVO" @selected(old('estado', $carrera->estado) === 'INACTIVO')>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Carrera
                        </button>
                        <a href="{{ route('carreras.show', $carrera->id_carrera) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
