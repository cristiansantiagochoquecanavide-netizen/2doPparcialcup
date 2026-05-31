@extends('layouts.app')

@section('titulo', 'Editar Requisito: ' . $requisito->nombre)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Requisito
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('requisitos.update', $requisito->id_requisito) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('requisitos._form', [
                        'requisito' => $requisito,
                        'submitText' => 'Actualizar Requisito',
                        'cancelUrl' => route('requisitos.show', $requisito->id_requisito),
                    ])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
