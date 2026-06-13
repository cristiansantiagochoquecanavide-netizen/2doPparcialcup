@extends('layouts.app')
<!-- Vista CU16: formulario de edicion de aula. -->
@section('titulo', 'Editar Aula')
@section('contenido')
<h1 class="page-title"><i class="bi bi-door-open"></i> Editar Aula <span class="badge bg-info ms-2">CU16</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('aulas.update', $aula->id_aula) }}">@csrf @method('PUT') @include('gestion-academica.aulas.form')<button class="btn btn-primary">Actualizar</button> <a href="{{ route('aulas.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
