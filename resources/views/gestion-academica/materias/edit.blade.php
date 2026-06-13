@extends('layouts.app')
<!-- Vista CU15: formulario de edicion de materia. -->
@section('titulo', 'Editar Materia')
@section('contenido')
<h1 class="page-title"><i class="bi bi-book"></i> Editar Materia <span class="badge bg-info ms-2">CU15</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('materias.update', $materia->id_materia) }}">@csrf @method('PUT') @include('gestion-academica.materias.form')<button class="btn btn-primary">Actualizar</button> <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
