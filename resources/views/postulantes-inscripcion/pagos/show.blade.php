@extends('layouts.app')
<!-- Vista CU8: detalle del pago asociado al postulante. -->

@section('titulo', 'Detalle Pago')

@section('contenido')
<h1 class="page-title"><i class="bi bi-cash-coin"></i> Detalle Pago <span class="badge bg-info ms-2">CU8</span></h1>
<div class="card"><div class="card-body">
    <p><strong>Postulante:</strong> {{ $pago->postulante->ci ?? '-' }} - {{ $pago->postulante->nombres ?? '' }} {{ $pago->postulante->apellidos ?? '' }}</p>
    <p><strong>Monto:</strong> {{ number_format($pago->monto, 2) }}</p>
    <p><strong>Metodo:</strong> {{ $pago->metodo_pago }}</p>
    <p><strong>Transaccion:</strong> {{ $pago->codigo_transaccion ?? '-' }}</p>
    <p><strong>Estado:</strong> {{ $pago->estado_pago }}</p>
    <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
</div></div>
@endsection
