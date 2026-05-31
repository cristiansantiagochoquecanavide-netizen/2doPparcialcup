@extends('layouts.app')
<!-- Vista de CU7: Validar requisitos. Muestra requisitos configurados para postulantes. -->

@section('titulo', 'Gestion de Requisitos')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-checklist"></i> Gestion de Requisitos
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('requisitos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Requisito
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Obligatorio</th>
                    <th>Postulantes</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitos as $requisito)
                    <tr>
                        <td>{{ $requisito->nombre }}</td>
                        <td>{{ Str::limit($requisito->descripcion, 50) ?? '-' }}</td>
                        <td>
                            @if($requisito->obligatorio)
                                <span class="badge bg-danger">Si</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $requisito->postulantes_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($requisito->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('requisitos.show', $requisito->id_requisito) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('requisitos.edit', $requisito->id_requisito) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('requisitos.destroy', $requisito->id_requisito) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar requisito?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay requisitos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($requisitos->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $requisitos->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
