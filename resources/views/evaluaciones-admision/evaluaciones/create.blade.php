@extends('layouts.app')
<!-- Vista CU19: formulario para configurar una de las 3 evaluaciones por materia. -->
@section('titulo', 'Nueva Evaluacion')
@section('contenido')
<h1 class="page-title"><i class="bi bi-list-check"></i> Nueva Evaluacion <span class="badge bg-info ms-2">CU19</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('evaluaciones.store') }}">@csrf @include('evaluaciones-admision.evaluaciones.form')<button class="btn btn-primary">Guardar</button> <a href="{{ route('evaluaciones.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
