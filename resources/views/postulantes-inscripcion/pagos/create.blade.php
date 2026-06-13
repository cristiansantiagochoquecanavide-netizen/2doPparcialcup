@extends('layouts.app')
<!-- Vista CU8: formulario para registrar pago con postulante, monto, metodo, transaccion y estado. -->

@section('titulo', 'Nuevo Pago')

@section('contenido')
<h1 class="page-title"><i class="bi bi-cash-coin"></i> Nuevo Pago <span class="badge bg-info ms-2">CU8</span></h1>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('pagos.store') }}">
        @csrf
        @include('postulantes-inscripcion.pagos.form')
        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div></div>
@endsection
