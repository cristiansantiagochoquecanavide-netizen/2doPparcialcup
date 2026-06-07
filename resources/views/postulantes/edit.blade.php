@extends('layouts.app')
<!-- Vista de CU6: Gestionar postulantes. Formulario para actualizar postulantes. -->

@section('titulo', 'Editar Postulante: ' . $postulante->nombres)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Postulante
    <span class="badge bg-info ms-2">CU6</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('postulantes.update', $postulante->id_postulante) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="ci" class="form-label">
                            <i class="bi bi-credit-card"></i> CI
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('ci') is-invalid @enderror" 
                            id="ci" 
                            name="ci"
                            value="{{ old('ci', $postulante->ci) }}"
                            required
                        >
                        @error('ci')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombres" class="form-label">
                                <i class="bi bi-person"></i> Nombres
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('nombres') is-invalid @enderror" 
                                id="nombres" 
                                name="nombres"
                                value="{{ old('nombres', $postulante->nombres) }}"
                                required
                            >
                            @error('nombres')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="apellidos" class="form-label">
                                <i class="bi bi-person"></i> Apellidos
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('apellidos') is-invalid @enderror" 
                                id="apellidos" 
                                name="apellidos"
                                value="{{ old('apellidos', $postulante->apellidos) }}"
                                required
                            >
                            @error('apellidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sexo" class="form-label">
                                <i class="bi bi-venus-mars"></i> Sexo
                            </label>
                            <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="M" @selected(old('sexo', $postulante->sexo) === 'M')>Masculino</option>
                                <option value="F" @selected(old('sexo', $postulante->sexo) === 'F')>Femenino</option>
                            </select>
                            @error('sexo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">
                                <i class="bi bi-envelope"></i> Correo Electrónico
                            </label>
                            <input 
                                type="email" 
                                class="form-control @error('correo') is-invalid @enderror" 
                                id="correo" 
                                name="correo"
                                value="{{ old('correo', $postulante->correo) }}"
                            >
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">
                                <i class="bi bi-calendar"></i> Fecha de Nacimiento
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                id="fecha_nacimiento" 
                                name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento', $postulante->fecha_nacimiento?->format('Y-m-d')) }}"
                            >
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_presentacion_formulario" class="form-label">
                                <i class="bi bi-calendar-check"></i> Fecha Presentación Formulario
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('fecha_presentacion_formulario') is-invalid @enderror" 
                                id="fecha_presentacion_formulario" 
                                name="fecha_presentacion_formulario"
                                value="{{ old('fecha_presentacion_formulario', $postulante->fecha_presentacion_formulario?->format('Y-m-d')) }}"
                            >
                            @error('fecha_presentacion_formulario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_carrera_primera_opcion" class="form-label">
                            <i class="bi bi-mortarboard"></i> Carrera 1ª Opción
                        </label>
                        <select class="form-select @error('id_carrera_primera_opcion') is-invalid @enderror" 
                                id="id_carrera_primera_opcion" name="id_carrera_primera_opcion">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera_primera_opcion', $postulante->id_carrera_primera_opcion) == $carrera->id_carrera)>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_carrera_primera_opcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_carrera_segunda_opcion" class="form-label">
                            <i class="bi bi-mortarboard"></i> Carrera 2ª Opción
                        </label>
                        <select class="form-select" id="id_carrera_segunda_opcion" name="id_carrera_segunda_opcion">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera_segunda_opcion', $postulante->id_carrera_segunda_opcion) == $carrera->id_carrera)>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Postulante
                        </button>
                        <a href="{{ route('postulantes.show', $postulante->id_postulante) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
