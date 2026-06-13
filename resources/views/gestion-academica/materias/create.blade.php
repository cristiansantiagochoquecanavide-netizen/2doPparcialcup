@extends('layouts.app')
<!-- Vista CU15: formulario de registro de materia. -->
@section('titulo', 'Nueva Materia')
@section('contenido')
<h1 class="page-title"><i class="bi bi-book"></i> Nueva Materia <span class="badge bg-info ms-2">CU15</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('materias.store') }}">@csrf @include('gestion-academica.materias.form')<button class="btn btn-primary">Guardar</button> <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
