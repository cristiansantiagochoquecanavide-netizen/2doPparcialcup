@extends('layouts.app')

@section('titulo', 'Crear Docente')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-plus-circle"></i> Nuevo Docente
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
