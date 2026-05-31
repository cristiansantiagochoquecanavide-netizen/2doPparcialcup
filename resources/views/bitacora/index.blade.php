@extends('layouts.app')

@section('titulo', 'Bitácora del Sistema')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-clock-history"></i> Bitácora del Sistema
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('bitacora.filtrar') }}" class="btn btn-info">
            <i class="bi bi-funnel"></i> Filtrar
        </a>
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
                        <td colspan="6" class="text-center text-muted">No hay registros de bitácora</td>
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
