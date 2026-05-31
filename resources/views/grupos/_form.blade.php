<!-- Seccion de formulario reutilizable para crear y editar grupos del CU12. -->
<div class="mb-3">
    <label for="codigo_grupo" class="form-label">
        <i class="bi bi-code"></i> Codigo del grupo
    </label>
    <input
        type="text"
        class="form-control @error('codigo_grupo') is-invalid @enderror"
        id="codigo_grupo"
        name="codigo_grupo"
        value="{{ old('codigo_grupo', $grupo->codigo_grupo ?? '') }}"
        placeholder="Ej: GR-001"
        required
    >
    @error('codigo_grupo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="cupo_maximo" class="form-label">
        <i class="bi bi-diagram-3"></i> Cupo maximo
    </label>
    <input
        type="number"
        class="form-control @error('cupo_maximo') is-invalid @enderror"
        id="cupo_maximo"
        name="cupo_maximo"
        value="{{ old('cupo_maximo', $grupo->cupo_maximo ?? 70) }}"
        min="1"
        max="200"
        required
    >
    @error('cupo_maximo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="estado" class="form-label">
        <i class="bi bi-toggle-on"></i> Estado
    </label>
    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
        <option value="ACTIVO" @selected(old('estado', $grupo->estado ?? 'ACTIVO') === 'ACTIVO')>Activo</option>
        <option value="INACTIVO" @selected(old('estado', $grupo->estado ?? 'ACTIVO') === 'INACTIVO')>Inactivo</option>
    </select>
    @error('estado')
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
