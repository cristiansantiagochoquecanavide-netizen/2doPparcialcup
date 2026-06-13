@extends('layouts.app')
<!-- Vista CU16: formulario de registro de aula. -->
@section('titulo', 'Nueva Aula')
@section('contenido')
<h1 class="page-title"><i class="bi bi-door-open"></i> Nueva Aula <span class="badge bg-info ms-2">CU16</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('aulas.store') }}">@csrf @include('gestion-academica.aulas.form')<button class="btn btn-primary">Guardar</button> <a href="{{ route('aulas.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
