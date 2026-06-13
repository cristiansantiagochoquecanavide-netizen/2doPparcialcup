<!-- Seccion de formulario reutilizable para crear y editar docentes del CU14. -->
@php
    $estadosContratacion = [
        'ACTIVO' => 'Activo',
        'INACTIVO' => 'Inactivo',
        'LICENCIA' => 'Licencia',
        'JUBILADO' => 'Jubilado',
    ];
@endphp

<div class="mb-3">
    <label for="ci" class="form-label">
        <i class="bi bi-credit-card"></i> CI
    </label>
    <input
        type="text"
        class="form-control @error('ci') is-invalid @enderror"
        id="ci"
        name="ci"
        value="{{ old('ci', $docente->ci ?? '') }}"
        placeholder="Ej: 123456789"
        required
    >
    @error('ci')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nombres" class="form-label">
            <i class="bi bi-person"></i> Nombres
        </label>
        <input
            type="text"
            class="form-control @error('nombres') is-invalid @enderror"
            id="nombres"
            name="nombres"
            value="{{ old('nombres', $docente->nombres ?? '') }}"
            required
        >
        @error('nombres')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="apellidos" class="form-label">
            <i class="bi bi-person"></i> Apellidos
        </label>
        <input
            type="text"
            class="form-control @error('apellidos') is-invalid @enderror"
            id="apellidos"
            name="apellidos"
            value="{{ old('apellidos', $docente->apellidos ?? '') }}"
            required
        >
        @error('apellidos')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="telefono" class="form-label">
            <i class="bi bi-telephone"></i> Telefono
        </label>
        <input
            type="text"
            class="form-control @error('telefono') is-invalid @enderror"
            id="telefono"
            name="telefono"
            value="{{ old('telefono', $docente->telefono ?? '') }}"
            placeholder="Ej: +591-7XXXXXXX"
        >
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="correo" class="form-label">
            <i class="bi bi-envelope"></i> Correo electronico
        </label>
        <input
            type="email"
            class="form-control @error('correo') is-invalid @enderror"
            id="correo"
            name="correo"
            value="{{ old('correo', $docente->correo ?? '') }}"
        >
        @error('correo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="profesional_area" class="form-label">
        <i class="bi bi-briefcase"></i> Area profesional
    </label>
    <input
        type="text"
        class="form-control @error('profesional_area') is-invalid @enderror"
        id="profesional_area"
        name="profesional_area"
        value="{{ old('profesional_area', $docente->profesional_area ?? '') }}"
        placeholder="Ej: Ingenieria de Sistemas"
    >
    @error('profesional_area')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-check">
            <input type="hidden" name="tiene_maestria" value="0">
            <input
                class="form-check-input @error('tiene_maestria') is-invalid @enderror"
                type="checkbox"
                id="tiene_maestria"
                name="tiene_maestria"
                value="1"
                @checked(old('tiene_maestria', $docente->tiene_maestria ?? false))
            >
            <label class="form-check-label" for="tiene_maestria">
                <i class="bi bi-mortarboard"></i> Tiene maestria
            </label>
            @error('tiene_maestria')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-check">
            <input type="hidden" name="tiene_diplomado_educacion_superior" value="0">
            <input
                class="form-check-input @error('tiene_diplomado_educacion_superior') is-invalid @enderror"
                type="checkbox"
                id="tiene_diplomado_educacion_superior"
                name="tiene_diplomado_educacion_superior"
                value="1"
                @checked(old('tiene_diplomado_educacion_superior', $docente->tiene_diplomado_educacion_superior ?? false))
            >
            <label class="form-check-label" for="tiene_diplomado_educacion_superior">
                <i class="bi bi-mortarboard"></i> Tiene diplomado en educacion superior
            </label>
            @error('tiene_diplomado_educacion_superior')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="estado_contratacion" class="form-label">
        <i class="bi bi-briefcase"></i> Estado de contratacion
    </label>
    <select
        class="form-select @error('estado_contratacion') is-invalid @enderror"
        id="estado_contratacion"
        name="estado_contratacion"
        required
    >
        <option value="">-- Seleccionar --</option>
        @foreach($estadosContratacion as $valor => $texto)
            <option value="{{ $valor }}" @selected(old('estado_contratacion', $docente->estado_contratacion ?? 'ACTIVO') === $valor)>
                {{ $texto }}
            </option>
        @endforeach
    </select>
    @error('estado_contratacion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> {{ $submitText }}
    </button>
    <a href="{{ $cancelUrl }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Cancelar
    </a>
</div>
