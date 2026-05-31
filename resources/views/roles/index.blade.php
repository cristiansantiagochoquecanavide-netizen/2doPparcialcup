@extends('layouts.app')

@section('titulo', 'Gestión de Roles')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-shield-lock"></i> Gestión de Roles
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Rol
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Permisos</th>
                    <th>Usuarios</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $rol)
                    <tr>
                        <td>{{ $rol->nombre }}</td>
                        <td>{{ $rol->descripcion ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $rol->permisos->count() }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $rol->usuarios->count() }}</span>
                        </td>
                        <td>
                            @if($rol->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('roles.show', $rol->id_rol) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('roles.edit', $rol->id_rol) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('roles.asignar-permisos', $rol->id_rol) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-key"></i>
                            </a>
                            <form action="{{ route('roles.destroy', $rol->id_rol) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar rol?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay roles registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($roles->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $roles->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
