@extends('layouts.app')
<!-- Vista CU20: formulario para editar nota existente. -->
@section('titulo', 'Editar Nota')
@section('contenido')
<h1 class="page-title"><i class="bi bi-pencil-square"></i> Editar Nota <span class="badge bg-info ms-2">CU20</span></h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('notas.update', $nota->id_nota) }}">@csrf @method('PUT') @include('evaluaciones-admision.notas.form')<button class="btn btn-primary">Actualizar</button> <a href="{{ route('notas.index') }}" class="btn btn-secondary">Volver</a></form></div></div>
@endsection
