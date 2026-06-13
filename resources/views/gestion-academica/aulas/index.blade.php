@extends('layouts.app')
<!-- Vista CU16: listar, buscar y eliminar aulas. -->
@section('titulo', 'Aulas')
@section('contenido')
<h1 class="page-title"><i class="bi bi-door-open"></i> Aulas <span class="badge bg-info ms-2">CU16</span></h1>
<form method="GET" class="row g-2 mb-3"><div class="col-md-6"><input name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por codigo, nombre o ubicacion"></div><div class="col-md-6"><button class="btn btn-primary">Buscar</button> <a href="{{ route('aulas.create') }}" class="btn btn-success">Nueva aula</a></div></form>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Codigo</th><th>Nombre</th><th>Capacidad</th><th>Ubicacion</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($aulas as $aula)
<tr><td>{{ $aula->codigo }}</td><td>{{ $aula->nombre }}</td><td>{{ $aula->capacidad }}</td><td>{{ $aula->ubicacion ?? '-' }}</td><td><span class="badge bg-secondary">{{ $aula->estado }}</span></td><td><a href="{{ route('aulas.edit', $aula->id_aula) }}" class="btn btn-sm btn-warning">Editar</a> <form action="{{ route('aulas.destroy', $aula->id_aula) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar aula?')">Eliminar</button></form></td></tr>
@empty
<tr><td colspan="6" class="text-center text-muted">No hay aulas</td></tr>
@endforelse
</tbody></table>{{ $aulas->links() }}</div></div>
@endsection
