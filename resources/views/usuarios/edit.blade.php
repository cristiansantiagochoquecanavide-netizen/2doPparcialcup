@extends('layouts.app')

@section('titulo', 'Editar Usuario: ' . $usuario->nombre_usuario)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Usuario
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('usuarios.update', $usuario->id_usuario) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre_usuario" class="form-label">
                            <i class="bi bi-person"></i> Nombre de Usuario
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('nombre_usuario') is-invalid @enderror" 
                            id="nombre_usuario" 
                            name="nombre_usuario"
                            value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}"
                            required
                        >
                        @error('nombre_usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">
                            <i class="bi bi-envelope"></i> Correo Electrónico
                        </label>
                        <input 
                            type="email" 
                            class="form-control @error('correo') is-invalid @enderror" 
                            id="correo" 
                            name="correo"
                            value="{{ old('correo', $usuario->correo) }}"
                            required
                        >
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_rol" class="form-label">
                            <i class="bi bi-shield-lock"></i> Rol
                        </label>
                        <select 
                            class="form-select @error('id_rol') is-invalid @enderror" 
                            id="id_rol" 
                            name="id_rol"
                            required
                        >
                            <option value="">-- Seleccionar Rol --</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}" @selected(old('id_rol', $usuario->id_rol) == $rol->id_rol)>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_rol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Deje los campos de contraseña vacíos si no desea cambiarla.
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Nueva Contraseña (Opcional)
                        </label>
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password"
                            placeholder="Mínimo 8 caracteres"
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock"></i> Confirmar Contraseña
                        </label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            placeholder="Confirme la nueva contraseña"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">
                            <i class="bi bi-toggle-on"></i> Estado
                        </label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="ACTIVO" @selected(old('estado', $usuario->estado) === 'ACTIVO')>Activo</option>
                            <option value="INACTIVO" @selected(old('estado', $usuario->estado) === 'INACTIVO')>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Usuario
                        </button>
                        <a href="{{ route('usuarios.show', $usuario->id_usuario) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
