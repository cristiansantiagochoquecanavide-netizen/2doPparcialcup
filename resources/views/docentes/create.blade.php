@extends('layouts.app')
<!-- Vista de CU14: Gestionar docentes. Formulario para registrar docentes. -->

@section('titulo', 'Crear Docente')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-plus-circle"></i> Nuevo Docente
    <span class="badge bg-info ms-2">CU14</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('docentes.store') }}" method="POST">
                    @csrf

                    @include('docentes._form', [
                        'docente' => null,
                        'submitText' => 'Crear Docente',
                        'cancelUrl' => route('docentes.index'),
                    ])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
