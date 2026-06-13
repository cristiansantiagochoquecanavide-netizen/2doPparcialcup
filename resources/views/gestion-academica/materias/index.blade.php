@extends('layouts.app')
<!-- Vista CU15: listar, buscar y eliminar materias del CUP. -->

@section('titulo', 'Materias')

@section('contenido')
<h1 class="page-title"><i class="bi bi-book"></i> Materias <span class="badge bg-info ms-2">CU15</span></h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar materia"></div><div class="col-md-6"><button class="btn btn-primary">Buscar</button> <a href="{{ route('materias.create') }}" class="btn btn-success">Nueva materia</a></div></form>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover">
<thead><tr><th>Nombre</th><th>Descripcion</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($materias as $materia)
<tr><td>{{ $materia->nombre }}</td><td>{{ $materia->descripcion ?? '-' }}</td><td><span class="badge bg-secondary">{{ $materia->estado }}</span></td><td><a href="{{ route('materias.edit', $materia->id_materia) }}" class="btn btn-sm btn-warning">Editar</a> <form action="{{ route('materias.destroy', $materia->id_materia) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar materia?')">Eliminar</button></form></td></tr>
@empty
<tr><td colspan="4" class="text-center text-muted">No hay materias</td></tr>
@endforelse
</tbody></table>{{ $materias->links() }}</div></div>
@endsection
