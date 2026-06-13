@extends('layouts.app')
<!-- Vista CU9: listado de inscripciones formalizadas. -->

@section('titulo', 'Inscripciones')

@section('contenido')
<h1 class="page-title"><i class="bi bi-journal-check"></i> Inscripciones <span class="badge bg-info ms-2">CU9</span></h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-6"><input name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por CI, nombre o apellido"></div>
    <div class="col-md-6"><button class="btn btn-primary">Buscar</button> <a href="{{ route('inscripciones.create') }}" class="btn btn-success">Nueva inscripcion</a></div>
</form>
<div class="card"><div class="card-body table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Postulante</th><th>Gestion</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($inscripciones as $inscripcion)
            <tr>
                <td>{{ $inscripcion->postulante->ci ?? '-' }} - {{ $inscripcion->postulante->nombres ?? '' }} {{ $inscripcion->postulante->apellidos ?? '' }}</td>
                <td>{{ $inscripcion->gestion->nombre ?? '-' }}</td>
                <td><span class="badge bg-success">{{ $inscripcion->estado_inscripcion }}</span></td>
                <td>{{ optional($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('inscripciones.show', $inscripcion->id_inscripcion) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <form action="{{ route('inscripciones.destroy', $inscripcion->id_inscripcion) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar inscripcion?')"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">No hay inscripciones registradas</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $inscripciones->links() }}
</div></div>
@endsection
