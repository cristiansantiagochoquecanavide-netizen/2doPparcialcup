<!-- Parcial CU17: datos de grupo, materia, docente, aula y horario. -->
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Grupo</label><select name="id_grupo" class="form-select" required><option value="">Seleccione...</option>@foreach($grupos as $grupo)<option value="{{ $grupo->id_grupo }}" @selected(old('id_grupo', $carga->id_grupo) == $grupo->id_grupo)>{{ $grupo->codigo_grupo }} - {{ $grupo->gestion->nombre ?? '-' }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Materia</label><select name="id_materia" class="form-select" required><option value="">Seleccione...</option>@foreach($materias as $materia)<option value="{{ $materia->id_materia }}" @selected(old('id_materia', $carga->id_materia) == $materia->id_materia)>{{ $materia->nombre }}</option>@endforeach</select></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Docente</label><select name="id_docente" class="form-select" required><option value="">Seleccione...</option>@foreach($docentes as $docente)<option value="{{ $docente->id_docente }}" @selected(old('id_docente', $carga->id_docente) == $docente->id_docente)>{{ $docente->nombres }} {{ $docente->apellidos }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Aula</label><select name="id_aula" class="form-select" required><option value="">Seleccione...</option>@foreach($aulas as $aula)<option value="{{ $aula->id_aula }}" @selected(old('id_aula', $carga->id_aula) == $aula->id_aula)>{{ $aula->codigo }} - cap. {{ $aula->capacidad }}</option>@endforeach</select></div>
</div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Dia</label><select name="dia_semana" class="form-select" required>@foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $dia)<option value="{{ $dia }}" @selected(old('dia_semana', $carga->dia_semana) === $dia)>{{ $dia }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label class="form-label">Hora inicio</label><input type="time" name="hora_inicio" value="{{ old('hora_inicio', $carga->hora_inicio ? \Illuminate\Support\Carbon::parse($carga->hora_inicio)->format('H:i') : '') }}" class="form-control" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">Hora fin</label><input type="time" name="hora_fin" value="{{ old('hora_fin', $carga->hora_fin ? \Illuminate\Support\Carbon::parse($carga->hora_fin)->format('H:i') : '') }}" class="form-control" required></div>
</div>
