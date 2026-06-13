@extends('layouts.app')
<!-- Vista de CU6: Gestionar postulantes. Muestra tabla y acciones del CRUD de postulantes. -->

@section('titulo', 'Gestion de Postulantes')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-file-person"></i> Gestion de Postulantes
    <span class="badge bg-info ms-2">CU6</span>
</h1>

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between gap-3 flex-wrap">
        <a href="{{ route('postulantes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Postulante
        </a>

        {{-- Buscador agregado: envia el parametro "buscar" al controlador para filtrar por CI, nombre, apellido o carrera. --}}
        <form action="{{ route('postulantes.index') }}" method="GET" class="d-flex gap-2">
            <input
                type="search"
                name="buscar"
                class="form-control"
                value="{{ $buscar ?? '' }}"
                placeholder="Buscar por CI, nombre, apellido o carrera"
                aria-label="Buscar postulantes"
            >
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i> Buscar
            </button>
            @if(!empty($buscar))
                <a href="{{ route('postulantes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CI</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Carrera 1ra Opcion</th>
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
                            <td>{{ $postulante->correo }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $postulante->carreraOpcionPrimera->nombre ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $color = match($postulante->estado) {
                                        'PENDIENTE_VALIDACION' => 'secondary',
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
                                <a href="{{ route('postulantes.show', $postulante->id_postulante) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('postulantes.edit', $postulante->id_postulante) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('postulantes.destroy', $postulante->id_postulante) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('Eliminar postulante?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                @if(!empty($buscar))
                                    No se encontraron postulantes para "{{ $buscar }}"
                                @else
                                    No hay postulantes registrados
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($postulantes->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{-- Conserva el texto buscado cuando se cambia de pagina. --}}
                {{ $postulantes->appends(['buscar' => $buscar ?? null])->links() }}
            </nav>
        @endif
    </div>
</div>
@endsection
