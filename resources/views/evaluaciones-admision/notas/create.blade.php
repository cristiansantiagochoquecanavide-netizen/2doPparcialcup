@extends('layouts.app')
<!-- Vista CU20: formulario para registrar nota entre 0 y 100. -->
@section('titulo', 'Nueva Nota')
@section('contenido')
<h1 class="page-title"><i class="bi bi-pencil-square"></i> Nueva Nota <span class="badge bg-info ms-2">CU20</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('notas.store') }}">@csrf @include('evaluaciones-admision.notas.form')<button class="btn btn-primary">Guardar</button> <a href="{{ route('notas.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
