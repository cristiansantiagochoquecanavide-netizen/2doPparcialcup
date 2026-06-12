@extends('layouts.app')
<!-- Vista de CU6: Gestionar postulantes. Formulario para actualizar postulantes. -->

@section('titulo', 'Editar Postulante: ' . $postulante->nombres)

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-pencil"></i> Editar Postulante
    <span class="badge bg-info ms-2">CU6</span>
</h1>

<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('postulantes.update', $postulante->id_postulante) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Datos personales obligatorios: se cargan con los valores actuales del postulante. --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="ci" class="form-label">CI <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ci') is-invalid @enderror" id="ci" name="ci" value="{{ old('ci', $postulante->ci) }}" required>
                            @error('ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres" value="{{ old('nombres', $postulante->nombres) }}" required>
                            @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $postulante->apellidos) }}" required>
                            @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Fecha, sexo y estado se pueden corregir desde la edicion principal. --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $postulante->fecha_nacimiento?->format('Y-m-d')) }}" required>
                            @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="M" @selected(old('sexo', $postulante->sexo) === 'M')>Masculino</option>
                                <option value="F" @selected(old('sexo', $postulante->sexo) === 'F')>Femenino</option>
                            </select>
                            @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="PENDIENTE_VALIDACION" @selected(old('estado', $postulante->estado) === 'PENDIENTE_VALIDACION')>PENDIENTE_VALIDACION</option>
                                <option value="REGISTRADO" @selected(old('estado', $postulante->estado) === 'REGISTRADO')>REGISTRADO</option>
                                <option value="VALIDADO" @selected(old('estado', $postulante->estado) === 'VALIDADO')>VALIDADO</option>
                                <option value="INSCRITO" @selected(old('estado', $postulante->estado) === 'INSCRITO')>INSCRITO</option>
                                <option value="RECHAZADO" @selected(old('estado', $postulante->estado) === 'RECHAZADO')>RECHAZADO</option>
                            </select>
                            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Datos de contacto: el correo sigue siendo obligatorio, valido y unico. --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">Correo electronico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $postulante->correo) }}" required>
                            @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $postulante->telefono) }}">
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Direccion</label>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $postulante->direccion) }}">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Datos del colegio de procedencia y ciudad declarada. --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="colegio_procedencia" class="form-label">Colegio de procedencia</label>
                            <input type="text" class="form-control @error('colegio_procedencia') is-invalid @enderror" id="colegio_procedencia" name="colegio_procedencia" value="{{ old('colegio_procedencia', $postulante->colegio_procedencia) }}">
                            @error('colegio_procedencia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $postulante->ciudad) }}">
                            @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Opciones de carrera: la primera es obligatoria y la segunda es opcional. --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_carrera_primera_opcion" class="form-label">Carrera de primera opcion <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_carrera_primera_opcion') is-invalid @enderror" id="id_carrera_primera_opcion" name="id_carrera_primera_opcion" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera_primera_opcion', $postulante->id_carrera_primera_opcion) == $carrera->id_carrera)>{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_carrera_primera_opcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_carrera_segunda_opcion" class="form-label">Carrera de segunda opcion</label>
                            <select class="form-select @error('id_carrera_segunda_opcion') is-invalid @enderror" id="id_carrera_segunda_opcion" name="id_carrera_segunda_opcion">
                                <option value="">-- Seleccionar --</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera_segunda_opcion', $postulante->id_carrera_segunda_opcion) == $carrera->id_carrera)>{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_carrera_segunda_opcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Documentacion adicional del postulante. --}}
                    <div class="mb-3">
                        <label for="titulo_bachiller" class="form-label">Titulo de bachiller</label>
                        <input type="text" class="form-control @error('titulo_bachiller') is-invalid @enderror" id="titulo_bachiller" name="titulo_bachiller" value="{{ old('titulo_bachiller', $postulante->titulo_bachiller) }}">
                        @error('titulo_bachiller')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="otros_requisitos" class="form-label">Otros requisitos</label>
                        <textarea class="form-control @error('otros_requisitos') is-invalid @enderror" id="otros_requisitos" name="otros_requisitos" rows="3">{{ old('otros_requisitos', $postulante->otros_requisitos) }}</textarea>
                        @error('otros_requisitos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Postulante
                        </button>
                        <a href="{{ route('postulantes.show', $postulante->id_postulante) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
