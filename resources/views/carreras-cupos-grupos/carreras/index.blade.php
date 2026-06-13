@extends('layouts.app')
<!-- Vista de CU10: Gestionar carreras. Muestra tabla y acciones del CRUD de carreras. -->

@section('titulo', 'Gestión de Carreras')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-mortarboard"></i> Gestión de Carreras
    <span class="badge bg-info ms-2">CU10</span>
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('carreras.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Carrera
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Postulantes</th>
                    <th>Grupos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carreras as $carrera)
                    <tr>
                        <td>{{ $carrera->id_carrera }}</td>
                        <td>{{ $carrera->codigo }}</td>
                        <td>{{ $carrera->nombre }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $carrera->postulantes_opcion_primera_count + $carrera->postulantes_opcion_segunda_count }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $carrera->grupos_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($carrera->estado === 'ACTIVA')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('carreras.show', $carrera->id_carrera) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('carreras.edit', $carrera->id_carrera) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('carreras.asignar-cupo', $carrera->id_carrera) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-diagram-3"></i>
                            </a>
                            <form action="{{ route('carreras.destroy', $carrera->id_carrera) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar carrera?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay carreras registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($carreras->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $carreras->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
