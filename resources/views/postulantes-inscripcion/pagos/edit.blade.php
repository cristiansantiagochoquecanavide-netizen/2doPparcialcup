@extends('layouts.app')
<!-- Vista CU8: formulario para editar pago de inscripcion. -->

@section('titulo', 'Editar Pago')

@section('contenido')
<h1 class="page-title"><i class="bi bi-cash-coin"></i> Editar Pago <span class="badge bg-info ms-2">CU8</span></h1>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('pagos.update', $pago->id_pago) }}">
        @csrf @method('PUT')
        @include('postulantes-inscripcion.pagos.form')
        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div></div>
@endsection
