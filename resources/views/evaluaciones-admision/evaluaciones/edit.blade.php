@extends('layouts.app')
<!-- Vista CU19: formulario de edicion de evaluacion configurada. -->
@section('titulo', 'Editar Evaluacion')
@section('contenido')
<h1 class="page-title"><i class="bi bi-list-check"></i> Editar Evaluacion <span class="badge bg-info ms-2">CU19</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('evaluaciones.update', $evaluacion->id_evaluacion) }}">@csrf @method('PUT') @include('evaluaciones-admision.evaluaciones.form')<button class="btn btn-primary">Actualizar</button> <a href="{{ route('evaluaciones.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
