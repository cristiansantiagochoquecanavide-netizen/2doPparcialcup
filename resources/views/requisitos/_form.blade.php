<div class="mb-3">
    <label for="nombre" class="form-label">
        <i class="bi bi-tag"></i> Nombre del requisito
    </label>
    <input
        type="text"
        class="form-control @error('nombre') is-invalid @enderror"
        id="nombre"
        name="nombre"
        value="{{ old('nombre', $requisito->nombre ?? '') }}"
        placeholder="Ej: Diploma de Bachiller"
        required
    >
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">
        <i class="bi bi-chat-left-text"></i> Descripcion
    </label>
    <textarea
        class="form-control @error('descripcion') is-invalid @enderror"
        id="descripcion"
        name="descripcion"
        rows="4"
        maxlength="200"
        placeholder="Descripcion del requisito"
    >{{ old('descripcion', $requisito->descripcion ?? '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="obligatorio" class="form-label">
        <i class="bi bi-exclamation-circle"></i> Obligatorio
    </label>
    <select class="form-select @error('obligatorio') is-invalid @enderror" id="obligatorio" name="obligatorio" required>
        <option value="1" @selected((string) old('obligatorio', (int) ($requisito->obligatorio ?? true)) === '1')>Si</option>
        <option value="0" @selected((string) old('obligatorio', (int) ($requisito->obligatorio ?? true)) === '0')>No</option>
    </select>
    @error('obligatorio')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="estado" class="form-label">
        <i class="bi bi-toggle-on"></i> Estado
    </label>
    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
        <option value="ACTIVO" @selected(old('estado', $requisito->estado ?? 'ACTIVO') === 'ACTIVO')>Activo</option>
        <option value="INACTIVO" @selected(old('estado', $requisito->estado ?? 'ACTIVO') === 'INACTIVO')>Inactivo</option>
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
