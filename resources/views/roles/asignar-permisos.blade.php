@extends('layouts.app')

@section('titulo', 'Asignar Permisos: ' . $rol->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-key"></i> Asignar Permisos a {{ $rol->nombre }}
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.guardar-permisos', $rol->id_rol) }}" method="POST">
                    @csrf

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Seleccione los permisos que desea asignar a este rol.
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-3">Permisos Disponibles</h6>
                        <div class="list-group">
                            @foreach($todosPermisos as $permiso)
                                <div class="list-group-item">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="permiso_{{ $permiso->id_permiso }}" 
                                            name="permisos[]" 
                                            value="{{ $permiso->id_permiso }}"
                                            @checked(in_array($permiso->id_permiso, $permisosAsignados))
                                        >
                                        <label class="form-check-label" for="permiso_{{ $permiso->id_permiso }}">
                                            <strong>{{ $permiso->nombre }}</strong>
                                            @if($permiso->descripcion)
                                                <br>
                                                <small class="text-muted">{{ $permiso->descripcion }}</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($todosPermisos->isEmpty())
                            <p class="text-muted">No hay permisos disponibles</p>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Permisos
                        </button>
                        <a href="{{ route('roles.show', $rol->id_rol) }}" class="btn btn-secondary">
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
                <h5 class="mb-0">Resumen</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Rol:</strong> {{ $rol->nombre }}
                </p>
                <p>
                    <strong>Permisos Asignados:</strong> {{ count($permisosAsignados) }}
                </p>
                <p>
                    <strong>Permisos Disponibles:</strong> {{ $todosPermisos->count() }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
