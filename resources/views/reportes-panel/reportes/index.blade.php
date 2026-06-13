@extends('layouts.app')
<!-- Vista de CU23: reportes administrativos obligatorios. -->

@section('titulo', 'Reportes')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-file-earmark-bar-graph"></i> Reportes
    <span class="badge bg-info ms-2">CU23</span>
</h1>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Filtros</h5>
    </div>
    <div class="card-body">
        {{-- CU23: filtros por gestion, carrera, grupo, materia, estado y rango de fechas. --}}
        <form action="{{ route('reportes.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label" for="id_gestion">Gestion academica</label>
                <select name="id_gestion" id="id_gestion" class="form-select">
                    <option value="">Todas</option>
                    @foreach($gestiones as $gestion)
                        <option value="{{ $gestion->id_gestion }}" @selected(($filtros['id_gestion'] ?? '') == $gestion->id_gestion)>{{ $gestion->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="id_carrera">Carrera</label>
                <select name="id_carrera" id="id_carrera" class="form-select">
                    <option value="">Todas</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id_carrera }}" @selected(($filtros['id_carrera'] ?? '') == $carrera->id_carrera)>{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="id_grupo">Grupo</label>
                <select name="id_grupo" id="id_grupo" class="form-select">
                    <option value="">Todos</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id_grupo }}" @selected(($filtros['id_grupo'] ?? '') == $grupo->id_grupo)>{{ $grupo->codigo_grupo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="id_materia">Materia</label>
                <select name="id_materia" id="id_materia" class="form-select">
                    <option value="">Todas</option>
                    @foreach($materias as $materia)
                        <option value="{{ $materia->id_materia }}" @selected(($filtros['id_materia'] ?? '') == $materia->id_materia)>{{ $materia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="estado">Estado postulante</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    @foreach(['PENDIENTE_VALIDACION', 'REGISTRADO', 'VALIDADO', 'INSCRITO', 'RECHAZADO'] as $estado)
                        <option value="{{ $estado }}" @selected(($filtros['estado'] ?? '') === $estado)>{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="fecha_inicio">Fecha inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $filtros['fecha_inicio'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="fecha_fin">Fecha fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ $filtros['fecha_fin'] ?? '' }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

{{-- CU23: tarjetas resumen de aprobados, reprobados, promedio y grupos habilitados. --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body"><h6>Aprobados</h6><p class="fs-3 fw-bold">{{ $aprobados->count() }}</p></div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body"><h6>Reprobados</h6><p class="fs-3 fw-bold">{{ $reprobados->count() }}</p></div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body"><h6>Promedio general</h6><p class="fs-3 fw-bold">{{ number_format($promedioGeneral, 2) }}</p></div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-center"><div class="card-body"><h6>Grupos habilitados</h6><p class="fs-3 fw-bold">{{ $gruposHabilitados->count() }}</p></div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Lista general de postulantes</h5></div>
    <div class="card-body table-responsive">
        <table class="table table-sm table-hover">
            <thead><tr><th>CI</th><th>Nombre</th><th>Correo</th><th>Carrera 1</th><th>Estado</th></tr></thead>
            <tbody>
                @forelse($postulantes as $postulante)
                    <tr>
                        <td>{{ $postulante->ci }}</td>
                        <td>{{ $postulante->nombres }} {{ $postulante->apellidos }}</td>
                        <td>{{ $postulante->correo }}</td>
                        <td>{{ $postulante->carreraOpcionPrimera->nombre ?? '-' }}</td>
                        <td>{{ $postulante->estado }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Sin postulantes</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Estadisticas por materia</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Materia</th><th>Notas</th><th>Promedio</th></tr></thead>
                    <tbody>
                        @foreach($estadisticasMateria as $materia)
                            <tr><td>{{ $materia->nombre }}</td><td>{{ $materia->total_notas }}</td><td>{{ number_format((float) $materia->promedio, 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Docentes por grupo</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Grupo</th><th>Materia</th><th>Docente</th><th>Aula</th></tr></thead>
                    <tbody>
                        @forelse($docentesPorGrupo as $carga)
                            <tr>
                                <td>{{ $carga->grupo->codigo_grupo ?? '-' }}</td>
                                <td>{{ $carga->materia->nombre ?? '-' }}</td>
                                <td>{{ trim(($carga->docente->nombres ?? '') . ' ' . ($carga->docente->apellidos ?? '')) ?: '-' }}</td>
                                <td>{{ $carga->aula->codigo ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Sin carga horaria</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0">Grupos con mayor cantidad de aprobados</h5></div>
    <div class="card-body table-responsive">
        <table class="table table-sm">
            <thead><tr><th>Grupo</th><th>Aprobados</th></tr></thead>
            <tbody>
                @forelse($gruposConAprobados as $grupo)
                    <tr><td>{{ $grupo->codigo_grupo }}</td><td>{{ $grupo->aprobados }}</td></tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">Sin resultados</td></tr>
                @endforelse
            </tbody>
        </table>
        <p class="text-muted small mb-0">Exportar a PDF o Excel queda como extension opcional del CU23.</p>
    </div>
</div>
@endsection
