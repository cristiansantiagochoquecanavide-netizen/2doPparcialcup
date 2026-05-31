@extends('layouts.app')
<!-- Vista de CU4: Gestionar roles. Formulario para registrar roles. -->

@section('titulo', 'Crear Rol')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-shield-plus"></i> Crear Nuevo Rol
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-tag"></i> Nombre del Rol
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('nombre') is-invalid @enderror" 
                            id="nombre" 
                            name="nombre"
                            value="{{ old('nombre') }}"
                            placeholder="Ej: Administrador, Editor, Consultor"
                            required
                        >
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="bi bi-chat-left-text"></i> Descripción
                        </label>
                        <textarea 
                            class="form-control @error('descripcion') is-invalid @enderror" 
                            id="descripcion" 
                            name="descripcion"
                            rows="4"
                            placeholder="Descripción del rol"
                        >{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">
                            <i class="bi bi-toggle-on"></i> Estado
                        </label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="ACTIVO" @selected(old('estado') === 'ACTIVO')>Activo</option>
                            <option value="INACTIVO" @selected(old('estado') === 'INACTIVO')>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Crear Rol
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
