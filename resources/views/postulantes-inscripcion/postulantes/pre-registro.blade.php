@extends('layouts.app')
<!-- Vista de CU25: formulario publico de pre-registro de postulante. -->

@section('titulo', 'Pre-registro de Postulante')

@section('contenido')
<div class="container py-5">
    <h1 class="page-title">
        <i class="bi bi-file-person"></i> Pre-registro de Postulante
        <span class="badge bg-info ms-2">CU25</span>
    </h1>

    <div class="card">
        <div class="card-body">
            {{-- CU25: formulario publico; deja al postulante PENDIENTE_VALIDACION para revision administrativa. --}}
            <form action="{{ route('postulantes.guardar-pre-registro') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="ci" class="form-label">CI <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ci') is-invalid @enderror" id="ci" name="ci" value="{{ old('ci') }}" required>
                        @error('ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                        @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
                        @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                        @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                        <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                            <option value="">-- Seleccionar --</option>
                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo') === 'F')>Femenino</option>
                        </select>
                        @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="correo" class="form-label">Correo electronico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" required>
                        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="direccion" class="form-label">Direccion</label>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="colegio_procedencia" class="form-label">Colegio de procedencia</label>
                        <input type="text" class="form-control @error('colegio_procedencia') is-invalid @enderror" id="colegio_procedencia" name="colegio_procedencia" value="{{ old('colegio_procedencia') }}">
                        @error('colegio_procedencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}">
                        @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_carrera_primera_opcion" class="form-label">Carrera primera opcion <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_carrera_primera_opcion') is-invalid @enderror" id="id_carrera_primera_opcion" name="id_carrera_primera_opcion" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera_primera_opcion') == $carrera->id_carrera)>{{ $carrera->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_carrera_primera_opcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_carrera_segunda_opcion" class="form-label">Carrera segunda opcion</label>
                        <select class="form-select @error('id_carrera_segunda_opcion') is-invalid @enderror" id="id_carrera_segunda_opcion" name="id_carrera_segunda_opcion">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera_segunda_opcion') == $carrera->id_carrera)>{{ $carrera->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_carrera_segunda_opcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="titulo_bachiller" class="form-label">Titulo de bachiller</label>
                    <input type="text" class="form-control @error('titulo_bachiller') is-invalid @enderror" id="titulo_bachiller" name="titulo_bachiller" value="{{ old('titulo_bachiller') }}">
                    @error('titulo_bachiller')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="otros_requisitos" class="form-label">Otros requisitos o documentos adjuntos</label>
                    <textarea class="form-control @error('otros_requisitos') is-invalid @enderror" id="otros_requisitos" name="otros_requisitos" rows="3">{{ old('otros_requisitos') }}</textarea>
                    @error('otros_requisitos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Describa los documentos presentados. La carga de archivos puede agregarse como extension posterior.</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Enviar pre-registro
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
