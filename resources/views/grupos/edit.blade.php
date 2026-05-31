@extends('layouts.app')
<!-- Vista de CU12: Gestionar grupos. Formulario para actualizar grupos. -->

@section('titulo', 'Editar Grupo: ' . $grupo->codigo_grupo)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Grupo
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('grupos.update', $grupo->id_grupo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('grupos._form', [
                        'grupo' => $grupo,
                        'submitText' => 'Actualizar Grupo',
                        'cancelUrl' => route('grupos.show', $grupo->id_grupo),
                    ])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
