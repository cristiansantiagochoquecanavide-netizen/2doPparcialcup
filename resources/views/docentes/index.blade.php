@extends('layouts.app')

@section('titulo', 'Gestion de Docentes')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-person-video2"></i> Gestion de Docentes
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('docentes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Docente
        </a>
        <a href="{{ route('docentes.filtrar') }}" class="btn btn-info">
            <i class="bi bi-search"></i> Filtrar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>CI</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Estado contratacion</th>
                    <th>Cargas Horarias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($docentes as $docente)
                    <tr>
                        <td>{{ $docente->ci }}</td>
                        <td>{{ $docente->nombres }}</td>
                        <td>{{ $docente->apellidos }}</td>
                        <td>{{ $docente->correo ?? '-' }}</td>
                        <td>
                            @php
                                $colorEstado = match($docente->estado_contratacion) {
                                    'ACTIVO' => 'success',
                                    'INACTIVO' => 'danger',
                                    'LICENCIA' => 'warning',
                                    'JUBILADO' => 'info',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $colorEstado }}">{{ $docente->estado_contratacion }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $docente->cargas_horarias_count ?? $docente->cargasHorarias->count() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('docentes.show', $docente->id_docente) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('docentes.edit', $docente->id_docente) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('docentes.destroy', $docente->id_docente) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar docente?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay docentes registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($docentes->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $docentes->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
