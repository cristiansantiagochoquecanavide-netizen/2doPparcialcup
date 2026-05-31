@extends('layouts.app')

@section('titulo', 'Filtrar Bitácora')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-funnel"></i> Filtrar Bitácora
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Filtros</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('bitacora.filtrar') }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_usuario" class="form-label">Usuario</label>
                            <select class="form-select" id="id_usuario" name="id_usuario">
                                <option value="">-- Todos --</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id_usuario }}" @selected(request('id_usuario') == $usuario->id_usuario)>
                                        {{ $usuario->nombre_usuario }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modulo" class="form-label">Módulo</label>
                            <select class="form-select" id="modulo" name="modulo">
                                <option value="">-- Todos --</option>
                                @foreach($modulos as $modulo)
                                    <option value="{{ $modulo }}" @selected(request('modulo') == $modulo)>
                                        {{ $modulo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="accion" class="form-label">Acción</label>
                            <select class="form-select" id="accion" name="accion">
                                <option value="">-- Todas --</option>
                                @foreach($acciones as $accion)
                                    <option value="{{ $accion }}" @selected(request('accion') == $accion)>
                                        {{ $accion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" 
                                   value="{{ request('descripcion') }}" placeholder="Búsqueda">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">Fecha Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="{{ request('fecha_fin') }}">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('bitacora.index') }}" class="btn btn-secondary">
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
                    <th>Fecha/Hora</th>
                    <th>Usuario</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bitacoras as $bitacora)
                    <tr>
                        <td>{{ $bitacora->fecha_hora->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $bitacora->usuario->nombre_usuario ?? '-' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $bitacora->modulo }}</span>
                        </td>
                        <td>
                            @php
                                $color = match($bitacora->accion) {
                                    'CREAR' => 'success',
                                    'ACTUALIZAR' => 'warning',
                                    'ELIMINAR' => 'danger',
                                    'LOGIN' => 'primary',
                                    'LOGOUT' => 'secondary',
                                    default => 'info'
                                };
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $bitacora->accion }}</span>
                        </td>
                        <td>{{ Str::limit($bitacora->descripcion, 50) }}</td>
                        <td>
                            <a href="{{ route('bitacora.show', $bitacora->id_bitacora) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay registros coincidentes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($bitacoras->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $bitacoras->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
