<!-- Parcial CU15: campos obligatorios y estado de materia. -->
<div class="mb-3"><label class="form-label">Nombre</label><input name="nombre" value="{{ old('nombre', $materia->nombre) }}" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Descripcion</label><input name="descripcion" value="{{ old('descripcion', $materia->descripcion) }}" class="form-control"></div>
<div class="mb-3"><label class="form-label">Estado</label><select name="estado" class="form-select" required>@foreach(['ACTIVA','INACTIVA'] as $estado)<option value="{{ $estado }}" @selected(old('estado', $materia->estado ?: 'ACTIVA') === $estado)>{{ $estado }}</option>@endforeach</select></div>
