@extends('layouts.app')
<!-- Vista de CU7: Validar requisitos. Formulario para registrar requisitos. -->

@section('titulo', 'Crear Requisito')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-plus-circle"></i> Nuevo Requisito
    <span class="badge bg-info ms-2">CU7</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('requisitos.store') }}" method="POST">
                    @csrf

                    @include('requisitos._form', [
                        'requisito' => null,
                        'submitText' => 'Crear Requisito',
                        'cancelUrl' => route('requisitos.index'),
                    ])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
