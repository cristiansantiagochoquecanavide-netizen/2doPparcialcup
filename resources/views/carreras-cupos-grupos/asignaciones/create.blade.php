@extends('layouts.app')
<!-- Vista CU13: formulario para asignar una inscripcion a un grupo activo con cupo. -->

@section('titulo', 'Asignar Estudiante')

@section('contenido')
<h1 class="page-title"><i class="bi bi-person-plus"></i> Asignar Estudiante <span class="badge bg-info ms-2">CU13</span></h1>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('grupo-estudiantes.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Grupo activo</label>
            <select name="id_grupo" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id_grupo }}" @selected(old('id_grupo') == $grupo->id_grupo)>{{ $grupo->codigo_grupo }} - cupo {{ $grupo->estudiantes_grupo_count }}/{{ $grupo->cupo_maximo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Inscripcion sin grupo</label>
            <select name="id_inscripcion" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($inscripciones as $inscripcion)
                    <option value="{{ $inscripcion->id_inscripcion }}" @selected(old('id_inscripcion') == $inscripcion->id_inscripcion)>{{ $inscripcion->postulante->ci ?? '-' }} - {{ $inscripcion->postulante->nombres ?? '' }} {{ $inscripcion->postulante->apellidos ?? '' }} / {{ $inscripcion->gestion->nombre ?? '-' }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Asignar</button>
        <a href="{{ route('grupo-estudiantes.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div></div>
@endsection
