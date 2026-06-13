@extends('layouts.app')
<!-- Vista CU19: listado de evaluaciones configuradas por materia y gestion. -->
@section('titulo', 'Evaluaciones')
@section('contenido')
<h1 class="page-title"><i class="bi bi-list-check"></i> Evaluaciones <span class="badge bg-info ms-2">CU19</span></h1>
<a href="{{ route('evaluaciones.create') }}" class="btn btn-success mb-3">Nueva evaluacion</a>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Gestion</th><th>Materia</th><th>Nro.</th><th>Porcentaje</th><th>Acciones</th></tr></thead><tbody>
@forelse($evaluaciones as $evaluacion)
<tr><td>{{ $evaluacion->gestion->nombre ?? '-' }}</td><td>{{ $evaluacion->materia->nombre ?? '-' }}</td><td>{{ $evaluacion->numero_evaluacion }}</td><td>{{ $evaluacion->porcentaje }}%</td><td><a href="{{ route('evaluaciones.edit', $evaluacion->id_evaluacion) }}" class="btn btn-sm btn-warning">Editar</a> <form action="{{ route('evaluaciones.destroy', $evaluacion->id_evaluacion) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar evaluacion?')">Eliminar</button></form></td></tr>
@empty
<tr><td colspan="5" class="text-center text-muted">No hay evaluaciones</td></tr>
@endforelse
</tbody></table>{{ $evaluaciones->links() }}</div></div>
@endsection
