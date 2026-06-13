@extends('layouts.app')
<!-- Vista CU9: formulario de inscripcion; el controlador valida requisitos, pago y gestion activa. -->

@section('titulo', 'Nueva Inscripcion')

@section('contenido')
<h1 class="page-title"><i class="bi bi-journal-check"></i> Nueva Inscripcion <span class="badge bg-info ms-2">CU9</span></h1>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('inscripciones.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Postulante</label>
            <select name="id_postulante" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($postulantes as $postulante)
                    <option value="{{ $postulante->id_postulante }}" @selected(old('id_postulante') == $postulante->id_postulante)>{{ $postulante->ci }} - {{ $postulante->nombres }} {{ $postulante->apellidos }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Gestion activa</label>
            <select name="id_gestion" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($gestiones as $gestion)
                    <option value="{{ $gestion->id_gestion }}" @selected(old('id_gestion') == $gestion->id_gestion)>{{ $gestion->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Formalizar inscripcion</button>
        <a href="{{ route('inscripciones.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div></div>
@endsection
