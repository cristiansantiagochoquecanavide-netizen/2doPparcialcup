@extends('layouts.app')
<!-- Vista CU17: formulario de edicion de carga horaria. -->
@section('titulo', 'Editar Carga Horaria')
@section('contenido')
<h1 class="page-title"><i class="bi bi-calendar-week"></i> Editar Carga Horaria <span class="badge bg-info ms-2">CU17</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('carga-horaria.update', $carga->id_carga_horaria) }}">@csrf @method('PUT') @include('gestion-academica.carga-horaria.form')<button class="btn btn-primary">Actualizar</button> <a href="{{ route('carga-horaria.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
