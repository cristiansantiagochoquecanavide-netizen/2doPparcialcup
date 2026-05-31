@extends('layouts.app')

@section('titulo', 'Editar Docente: ' . $docente->nombres)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Docente
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
