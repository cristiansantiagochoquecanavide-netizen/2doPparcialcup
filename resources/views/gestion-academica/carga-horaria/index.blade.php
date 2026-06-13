@extends('layouts.app')
<!-- Vista CU17: listado de carga horaria con grupo, materia, docente, aula y horario. -->
@section('titulo', 'Carga Horaria')
@section('contenido')
<h1 class="page-title"><i class="bi bi-calendar-week"></i> Carga Horaria <span class="badge bg-info ms-2">CU17</span></h1>
<a href="{{ route('carga-horaria.create') }}" class="btn btn-success mb-3">Nueva carga</a>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Grupo</th><th>Materia</th><th>Docente</th><th>Aula</th><th>Dia</th><th>Horario</th><th>Acciones</th></tr></thead><tbody>
@forelse($cargas as $carga)
<tr><td>{{ $carga->grupo->codigo_grupo ?? '-' }}</td><td>{{ $carga->materia->nombre ?? '-' }}</td><td>{{ $carga->docente->nombres ?? '' }} {{ $carga->docente->apellidos ?? '' }}</td><td>{{ $carga->aula->codigo ?? '-' }}</td><td>{{ $carga->dia_semana }}</td><td>{{ $carga->hora_inicio }} - {{ $carga->hora_fin }}</td><td><a href="{{ route('carga-horaria.edit', $carga->id_carga_horaria) }}" class="btn btn-sm btn-warning">Editar</a> <form action="{{ route('carga-horaria.destroy', $carga->id_carga_horaria) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar carga?')">Eliminar</button></form></td></tr>
@empty
<tr><td colspan="7" class="text-center text-muted">No hay carga horaria</td></tr>
@endforelse
</tbody></table>{{ $cargas->links() }}</div></div>
@endsection
