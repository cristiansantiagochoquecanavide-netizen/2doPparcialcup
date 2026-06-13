@extends('layouts.app')
<!-- Vista CU17: formulario para asignar carga horaria. -->
@section('titulo', 'Nueva Carga Horaria')
@section('contenido')
<h1 class="page-title"><i class="bi bi-calendar-week"></i> Nueva Carga Horaria <span class="badge bg-info ms-2">CU17</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('carga-horaria.store') }}">@csrf @include('gestion-academica.carga-horaria.form')<button class="btn btn-primary">Guardar</button> <a href="{{ route('carga-horaria.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
