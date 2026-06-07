@extends('layouts.app')
<!-- Vista de CU14: Gestionar docentes. Formulario para actualizar docentes. -->

@section('titulo', 'Editar Docente: ' . $docente->nombres)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Docente
    <span class="badge bg-info ms-2">CU14</span>
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('docentes.update', $docente->id_docente) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('docentes._form', [
                        'docente' => $docente,
                        'submitText' => 'Actualizar Docente',
                        'cancelUrl' => route('docentes.show', $docente->id_docente),
                    ])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
