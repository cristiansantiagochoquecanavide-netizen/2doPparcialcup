@extends('layouts.app')
<!-- Vista CU20: listado de notas registradas por inscripcion y evaluacion. -->
@section('titulo', 'Notas')
@section('contenido')
<h1 class="page-title"><i class="bi bi-pencil-square"></i> Notas <span class="badge bg-info ms-2">CU20</span></h1>
<a href="{{ route('notas.create') }}" class="btn btn-success mb-3">Nueva nota</a>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Estudiante</th><th>Materia</th><th>Gestion</th><th>Evaluacion</th><th>Nota</th><th>Acciones</th></tr></thead><tbody>
@forelse($notas as $nota)
<tr><td>{{ $nota->inscripcion->postulante->ci ?? '-' }} - {{ $nota->inscripcion->postulante->nombres ?? '' }} {{ $nota->inscripcion->postulante->apellidos ?? '' }}</td><td>{{ $nota->evaluacion->materia->nombre ?? '-' }}</td><td>{{ $nota->evaluacion->gestion->nombre ?? '-' }}</td><td>{{ $nota->evaluacion->numero_evaluacion }}</td><td>{{ $nota->nota }}</td><td><a href="{{ route('notas.edit', $nota->id_nota) }}" class="btn btn-sm btn-warning">Editar</a></td></tr>
@empty
<tr><td colspan="6" class="text-center text-muted">No hay notas</td></tr>
@endforelse
</tbody></table>{{ $notas->links() }}</div></div>
@endsection
