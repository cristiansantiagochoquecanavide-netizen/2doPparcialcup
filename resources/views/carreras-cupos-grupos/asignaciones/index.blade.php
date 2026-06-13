@extends('layouts.app')
<!-- Vista CU13: lista estudiantes asignados a grupos. -->

@section('titulo', 'Asignaciones a Grupos')

@section('contenido')
<h1 class="page-title"><i class="bi bi-person-plus"></i> Asignaciones a Grupos <span class="badge bg-info ms-2">CU13</span></h1>
<a href="{{ route('grupo-estudiantes.create') }}" class="btn btn-success mb-3">Nueva asignacion</a>
<div class="card"><div class="card-body table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Grupo</th><th>Gestion</th><th>Estudiante</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($asignaciones as $asignacion)
            <tr>
                <td>{{ $asignacion->grupo->codigo_grupo ?? '-' }}</td>
                <td>{{ $asignacion->grupo->gestion->nombre ?? '-' }}</td>
                <td>{{ $asignacion->inscripcion->postulante->ci ?? '-' }} - {{ $asignacion->inscripcion->postulante->nombres ?? '' }} {{ $asignacion->inscripcion->postulante->apellidos ?? '' }}</td>
                <td>{{ optional($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                <td><form action="{{ route('grupo-estudiantes.destroy', $asignacion->id_grupo_estudiante) }}" method="POST">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Quitar asignacion?')">Quitar</button></form></td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">Sin asignaciones</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $asignaciones->links() }}
</div></div>
@endsection
