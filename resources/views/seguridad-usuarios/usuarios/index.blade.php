@extends('layouts.app')
<!-- Vista de CU3: Gestionar usuarios. Muestra tabla y acciones del CRUD de usuarios. -->

@section('titulo', 'Gestión de Usuarios')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-people"></i> Gestión de Usuarios
    <span class="badge bg-info ms-2">CU3</span>
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td>{{ $usuario->nombre_usuario }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>
                            <span class="badge bg-info">{{ $usuario->rol->nombre ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if($usuario->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $usuario->fecha_creacion->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('usuarios.show', $usuario->id_usuario) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay usuarios registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($usuarios->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $usuarios->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
