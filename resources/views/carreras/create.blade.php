@extends('layouts.app')
<!-- Vista de CU10: Gestionar carreras. Formulario para registrar carreras. -->

@section('titulo', 'Crear Carrera')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-plus-circle"></i> Nueva Carrera
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('carreras.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="codigo" class="form-label">
                            <i class="bi bi-code"></i> Código
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('codigo') is-invalid @enderror" 
                            id="codigo" 
                            name="codigo"
                            value="{{ old('codigo') }}"
                            placeholder="Ej: LIC-SI, LIC-IAE"
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
                            value="{{ old('nombre') }}"
                            placeholder="Ej: Licenciatura en Sistemas Informáticos"
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
                            <option value="ACTIVA" @selected(old('estado') === 'ACTIVA')>Activo</option>
                            <option value="INACTIVA" @selected(old('estado') === 'INACTIVA')>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Carrera
                        </button>
                        <a href="{{ route('carreras.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
