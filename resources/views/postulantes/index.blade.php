@extends('layouts.app')
<!-- Vista de CU6: Gestionar postulantes. Muestra tabla y acciones del CRUD de postulantes. -->

@section('titulo', 'Gestión de Postulantes')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-file-person"></i> Gestión de Postulantes
    <span class="badge bg-info ms-2">CU6</span>
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('postulantes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Postulante
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CI</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Carrera 1ª Opción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($postulantes as $postulante)
                    <tr>
                        <td>{{ $postulante->id_postulante }}</td>
                        <td>{{ $postulante->ci }}</td>
                        <td>{{ $postulante->nombres }}</td>
                        <td>{{ $postulante->apellidos }}</td>
                        <td>{{ $postulante->correo ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $postulante->carreraOpcionPrimera->nombre ?? '-' }}
                            </span>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <a href="{{ route('postulantes.show', $postulante->id_postulante) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('postulantes.edit', $postulante->id_postulante) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('postulantes.destroy', $postulante->id_postulante) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar postulante?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay postulantes registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($postulantes->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $postulantes->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
