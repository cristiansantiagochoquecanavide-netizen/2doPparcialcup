@extends('layouts.app')
<!-- Vista CU8: lista pagos de inscripcion y permite buscar por postulante o transaccion. -->

@section('titulo', 'Pagos de Inscripcion')

@section('contenido')
<h1 class="page-title"><i class="bi bi-cash-coin"></i> Pagos <span class="badge bg-info ms-2">CU8</span></h1>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-6"><input name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por CI, nombre o transaccion"></div>
    <div class="col-md-6">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
        <a href="{{ route('pagos.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo pago</a>
    </div>
</form>
<div class="card"><div class="card-body table-responsive">
    <table class="table table-hover">
        <thead><tr><th>Postulante</th><th>Monto</th><th>Metodo</th><th>Transaccion</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @forelse($pagos as $pago)
            <tr>
                <td>{{ $pago->postulante->ci ?? '-' }} - {{ $pago->postulante->nombres ?? '' }} {{ $pago->postulante->apellidos ?? '' }}</td>
                <td>{{ number_format($pago->monto, 2) }}</td>
                <td>{{ $pago->metodo_pago }}</td>
                <td>{{ $pago->codigo_transaccion ?? '-' }}</td>
                <td><span class="badge bg-secondary">{{ $pago->estado_pago }}</span></td>
                <td>
                    <a href="{{ route('pagos.show', $pago->id_pago) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('pagos.edit', $pago->id_pago) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('pagos.destroy', $pago->id_pago) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar pago?')"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">No hay pagos registrados</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $pagos->links() }}
</div></div>
@endsection
