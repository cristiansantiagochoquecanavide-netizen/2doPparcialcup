@extends('layouts.app')
<!-- Vista de CU14: Gestionar docentes. Permite filtrar docentes por criterios. -->

@section('titulo', 'Filtrar Docentes')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-search"></i> Filtrar Docentes
    <span class="badge bg-info ms-2">CU14</span>
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Filtros de busqueda</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('docentes.filtrar') }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="buscar" class="form-label">Buscar</label>
                            <input
                                type="text"
                                class="form-control"
                                id="buscar"
                                name="buscar"
                                value="{{ request('buscar') }}"
                                placeholder="Nombre, apellido, CI o correo"
                            >
                        </div>
                        <div class="col-md-6">
                            <label for="estado_contratacion" class="form-label">Estado de contratacion</label>
                            <select class="form-select" id="estado_contratacion" name="estado_contratacion">
                                <option value="">-- Todos --</option>
                                <option value="ACTIVO" @selected(request('estado_contratacion') === 'ACTIVO')>Activo</option>
                                <option value="INACTIVO" @selected(request('estado_contratacion') === 'INACTIVO')>Inactivo</option>
                                <option value="LICENCIA" @selected(request('estado_contratacion') === 'LICENCIA')>Licencia</option>
                                <option value="JUBILADO" @selected(request('estado_contratacion') === 'JUBILADO')>Jubilado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tiene_maestria" class="form-label">Maestria</label>
                            <select class="form-select" id="tiene_maestria" name="tiene_maestria">
                                <option value="">-- Todos --</option>
                                <option value="1" @selected(request('tiene_maestria') === '1')>Con maestria</option>
                                <option value="0" @selected(request('tiene_maestria') === '0')>Sin maestria</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tiene_diplomado_educacion_superior" class="form-label">Diplomado en educacion superior</label>
                            <select class="form-select" id="tiene_diplomado_educacion_superior" name="tiene_diplomado_educacion_superior">
                                <option value="">-- Todos --</option>
                                <option value="1" @selected(request('tiene_diplomado_educacion_superior') === '1')>Con diplomado</option>
                                <option value="0" @selected(request('tiene_diplomado_educacion_superior') === '0')>Sin diplomado</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('docentes.filtrar') }}" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>
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
                    <th>Maestria</th>
                    <th>Diplomado</th>
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
                            @if($docente->tiene_maestria)
                                <span class="badge bg-success">Si</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($docente->tiene_diplomado_educacion_superior)
                                <span class="badge bg-success">Si</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('docentes.show', $docente->id_docente) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('docentes.edit', $docente->id_docente) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay docentes coincidentes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($docentes->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $docentes->appends(request()->query())->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
