@extends('layouts.app')
<!-- Vista CU18: detalle de asistencia individual registrada. -->
@section('titulo', 'Detalle Asistencia')
@section('contenido')
<h1 class="page-title"><i class="bi bi-clipboard-check"></i> Detalle Asistencia <span class="badge bg-info ms-2">CU18</span></h1>
<div class="card"><div class="card-body">
<p><strong>Fecha:</strong> {{ optional($asistencia->fecha_clase)->format('d/m/Y') }}</p><p><strong>Grupo:</strong> {{ $asistencia->cargaHoraria->grupo->codigo_grupo ?? '-' }}</p><p><strong>Materia:</strong> {{ $asistencia->cargaHoraria->materia->nombre ?? '-' }}</p><p><strong>Tema:</strong> {{ $asistencia->tema_avanzado ?? '-' }}</p>
<table class="table"><thead><tr><th>Estudiante</th><th>Estado</th><th>Observacion</th></tr></thead><tbody>@foreach($asistencia->asistenciasDetalle as $detalle)<tr><td>{{ $detalle->inscripcion->postulante->ci ?? '-' }} - {{ $detalle->inscripcion->postulante->nombres ?? '' }} {{ $detalle->inscripcion->postulante->apellidos ?? '' }}</td><td>{{ $detalle->estado_asistencia }}</td><td>{{ $detalle->observacion ?? '-' }}</td></tr>@endforeach</tbody></table>
<a href="{{ route('asistencias.index') }}" class="btn btn-secondary">Volver</a>
</div></div>
@endsection
