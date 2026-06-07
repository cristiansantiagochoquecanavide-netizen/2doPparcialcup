@extends('layouts.app')
<!-- Vista de CU12: Gestionar grupos. Formulario para registrar grupos. -->

@section('titulo', 'Crear Grupo')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-plus-circle"></i> Nuevo Grupo
    <span class="badge bg-info ms-2">CU12</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('grupos.store') }}" method="POST">
                    @csrf

                    @include('grupos._form', [
                        'grupo' => null,
                        'submitText' => 'Crear Grupo',
                        'cancelUrl' => route('grupos.index'),
                    ])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
