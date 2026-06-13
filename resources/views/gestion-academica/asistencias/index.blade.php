@extends('layouts.app')
<!-- Vista CU18: lista asistencias y ofrece registrar por carga horaria. -->
@section('titulo', 'Asistencias')
@section('contenido')
<h1 class="page-title"><i class="bi bi-clipboard-check"></i> Asistencias <span class="badge bg-info ms-2">CU18</span></h1>
<div class="card mb-3"><div class="card-body"><form method="GET" action="{{ route('asistencias.create') }}" class="row g-2"><div class="col-md-8"><select name="id_carga_horaria" class="form-select" required><option value="">Seleccione carga horaria...</option>@foreach($cargas as $carga)<option value="{{ $carga->id_carga_horaria }}">{{ $carga->grupo->codigo_grupo ?? '-' }} / {{ $carga->materia->nombre ?? '-' }} / {{ $carga->docente->nombres ?? '' }} {{ $carga->docente->apellidos ?? '' }}</option>@endforeach</select></div><div class="col-md-4"><button class="btn btn-success">Registrar asistencia</button></div></form></div></div>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Fecha</th><th>Grupo</th><th>Materia</th><th>Tema</th><th>Acciones</th></tr></thead><tbody>
@forelse($asistencias as $asistencia)
<tr><td>{{ optional($asistencia->fecha_clase)->format('d/m/Y') }}</td><td>{{ $asistencia->cargaHoraria->grupo->codigo_grupo ?? '-' }}</td><td>{{ $asistencia->cargaHoraria->materia->nombre ?? '-' }}</td><td>{{ $asistencia->tema_avanzado ?? '-' }}</td><td><a class="btn btn-sm btn-info" href="{{ route('asistencias.show', $asistencia->id_asistencia_clase) }}">Ver</a></td></tr>
@empty
<tr><td colspan="5" class="text-center text-muted">No hay asistencias</td></tr>
@endforelse
</tbody></table>{{ $asistencias->links() }}</div></div>
@endsection
