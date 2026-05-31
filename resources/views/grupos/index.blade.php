@extends('layouts.app')

@section('titulo', 'Gestion de Grupos')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-people"></i> Gestion de Grupos
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('grupos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Grupo
        </a>
        <a href="{{ route('grupos.calculadora') }}" class="btn btn-info">
            <i class="bi bi-calculator"></i> Calcular Grupos
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Gestion</th>
                    <th>Cupo maximo</th>
                    <th>Ocupacion</th>
                    <th>Estudiantes</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grupos as $grupo)
                    @php
                        $estudiantes = $grupo->estudiantes_grupo_count ?? 0;
                        $ocupacion = $grupo->cupo_maximo > 0 ? round(($estudiantes / $grupo->cupo_maximo) * 100) : 0;
                        $colorOcupacion = $ocupacion >= 80 ? 'danger' : ($ocupacion >= 50 ? 'warning' : 'success');
                    @endphp
                    <tr>
                        <td>{{ $grupo->codigo_grupo }}</td>
                        <td>{{ $grupo->gestion->nombre ?? '-' }}</td>
                        <td>{{ $grupo->cupo_maximo }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div
                                    class="progress-bar bg-{{ $colorOcupacion }}"
                                    role="progressbar"
                                    style="width: {{ min($ocupacion, 100) }}%;"
                                    aria-valuenow="{{ $ocupacion }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                >
                                    {{ $ocupacion }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $estudiantes }}</span>
                        </td>
                        <td>
                            @if($grupo->estado === 'ACTIVO')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('grupos.show', $grupo->id_grupo) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('grupos.edit', $grupo->id_grupo) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('grupos.destroy', $grupo->id_grupo) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar grupo?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay grupos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($grupos->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $grupos->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
