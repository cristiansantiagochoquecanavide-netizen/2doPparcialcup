@extends('layouts.app')
<!-- Vista CU18: registra fecha, tema y asistencia individual por estudiante. -->
@section('titulo', 'Registrar Asistencia')
@section('contenido')
<h1 class="page-title"><i class="bi bi-clipboard-check"></i> Registrar Asistencia <span class="badge bg-info ms-2">CU18</span></h1>
@if(!$cargaSeleccionada)
<div class="alert alert-info">Seleccione una carga horaria desde el listado de asistencias.</div>
@else
<div class="card"><div class="card-body">
<p><strong>{{ $cargaSeleccionada->grupo->codigo_grupo ?? '-' }}</strong> / {{ $cargaSeleccionada->materia->nombre ?? '-' }} / {{ $cargaSeleccionada->docente->nombres ?? '' }} {{ $cargaSeleccionada->docente->apellidos ?? '' }}</p>
<form method="POST" action="{{ route('asistencias.store') }}">@csrf
<input type="hidden" name="id_carga_horaria" value="{{ $cargaSeleccionada->id_carga_horaria }}">
<div class="row"><div class="col-md-4 mb-3"><label class="form-label">Fecha clase</label><input type="date" name="fecha_clase" value="{{ old('fecha_clase', now()->toDateString()) }}" class="form-control" required></div><div class="col-md-8 mb-3"><label class="form-label">Tema avanzado</label><input name="tema_avanzado" value="{{ old('tema_avanzado') }}" class="form-control"></div></div>
<table class="table"><thead><tr><th>Estudiante</th><th>Estado</th><th>Observacion</th></tr></thead><tbody>
@forelse($estudiantes as $inscripcion)
<tr><td>{{ $inscripcion->postulante->ci ?? '-' }} - {{ $inscripcion->postulante->nombres ?? '' }} {{ $inscripcion->postulante->apellidos ?? '' }}</td><td><select name="asistencias[{{ $inscripcion->id_inscripcion }}][estado_asistencia]" class="form-select">@foreach(['PRESENTE','AUSENTE','ATRASO','LICENCIA'] as $estado)<option value="{{ $estado }}">{{ $estado }}</option>@endforeach</select></td><td><input name="asistencias[{{ $inscripcion->id_inscripcion }}][observacion]" class="form-control"></td></tr>
@empty
<tr><td colspan="3" class="text-center text-muted">El grupo no tiene estudiantes asignados</td></tr>
@endforelse
</tbody></table>
<button class="btn btn-primary" @disabled($estudiantes->isEmpty())>Guardar asistencia</button> <a href="{{ route('asistencias.index') }}" class="btn btn-secondary">Volver</a>
</form></div></div>
@endif
@endsection
