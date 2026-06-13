@extends('layouts.app')
<!-- Vista CU9: detalle de inscripcion formalizada. -->

@section('titulo', 'Detalle Inscripcion')

@section('contenido')
<h1 class="page-title"><i class="bi bi-journal-check"></i> Detalle Inscripcion <span class="badge bg-info ms-2">CU9</span></h1>
<div class="card"><div class="card-body">
    <p><strong>Postulante:</strong> {{ $inscripcion->postulante->ci ?? '-' }} - {{ $inscripcion->postulante->nombres ?? '' }} {{ $inscripcion->postulante->apellidos ?? '' }}</p>
    <p><strong>Gestion:</strong> {{ $inscripcion->gestion->nombre ?? '-' }}</p>
    <p><strong>Estado:</strong> {{ $inscripcion->estado_inscripcion }}</p>
    <p><strong>Grupo:</strong> {{ $inscripcion->grupoEstudiante->grupo->codigo_grupo ?? 'Sin asignar' }}</p>
    <a href="{{ route('inscripciones.index') }}" class="btn btn-secondary">Volver</a>
</div></div>
@endsection
