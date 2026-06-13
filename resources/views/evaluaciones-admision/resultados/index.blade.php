@extends('layouts.app')
<!-- Vista CU21/CU22: calcula resultado final y asigna carrera admitida por cupo disponible. -->
@section('titulo', 'Resultados de Admision')
@section('contenido')
<h1 class="page-title"><i class="bi bi-award"></i> Resultados de Admision <span class="badge bg-info ms-2">CU21/CU22</span></h1>
<div class="alert alert-info">El promedio se calcula primero por materia con 3 evaluaciones y luego como promedio final general del postulante.</div>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Postulante</th><th>Gestion</th><th>Promedio</th><th>Estado</th><th>Carrera admitida</th><th>Acciones</th></tr></thead><tbody>
@forelse($inscripciones as $inscripcion)
@php($resultado = $inscripcion->resultadoAdmision)
<tr>
<td>{{ $inscripcion->postulante->ci ?? '-' }} - {{ $inscripcion->postulante->nombres ?? '' }} {{ $inscripcion->postulante->apellidos ?? '' }}</td>
<td>{{ $inscripcion->gestion->nombre ?? '-' }}</td>
<td>{{ $resultado->promedio_final ?? '-' }}</td>
<td>{{ $resultado->estado_resultado ?? 'SIN CALCULAR' }}</td>
<td>{{ $resultado->carreraAdmitida->nombre ?? 'Sin asignar' }}</td>
<td>
<form action="{{ route('resultados.calcular', $inscripcion->id_inscripcion) }}" method="POST" class="d-inline">@csrf <button class="btn btn-sm btn-primary">Calcular</button></form>
@if($resultado && $resultado->estado_resultado === 'APROBADO')
<form action="{{ route('resultados.asignar-carrera', $resultado->id_resultado) }}" method="POST" class="d-inline">@csrf <button class="btn btn-sm btn-success">Asignar carrera</button></form>
@endif
</td>
</tr>
@empty
<tr><td colspan="6" class="text-center text-muted">No hay inscripciones</td></tr>
@endforelse
</tbody></table>{{ $inscripciones->links() }}</div></div>
@endsection
