@extends('layouts.app')
<!-- Vista de CU24: Dashboard administrativo con indicadores reales. -->

@section('titulo', 'Dashboard')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-house"></i> Dashboard
    <span class="badge bg-info ms-2">CU24</span>
</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-check"></i> Bienvenido, {{ Auth::user()->nombre_usuario }}
                </h5>
                <p class="card-text text-muted mb-0">
                    Rol: <strong>{{ Auth::user()->rol->nombre ?? 'Sin rol' }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- CU24: tarjetas principales con datos reales de la base de datos. --}}
<div class="row g-3">
    <div class="col-md-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-person-lines-fill text-primary" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Inscritos</h6>
                <p class="fs-4 fw-bold mb-0">{{ $indicadores['total_inscritos'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Aprobados</h6>
                <p class="fs-4 fw-bold mb-0">{{ $indicadores['total_aprobados'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Reprobados</h6>
                <p class="fs-4 fw-bold mb-0">{{ $indicadores['total_reprobados'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-collection text-warning" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Grupos</h6>
                <p class="fs-4 fw-bold mb-0">{{ $indicadores['total_grupos_habilitados'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-file-person text-info" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Postulantes</h6>
                <p class="fs-4 fw-bold mb-0">{{ $indicadores['total_postulantes'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-bar-chart text-secondary" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Promedio</h6>
                <p class="fs-4 fw-bold mb-0">{{ number_format($indicadores['promedio_general'], 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Estadisticas por materia</h5>
            </div>
            <div class="card-body">
                {{-- CU24: tabla real de notas y promedio por materia. --}}
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Notas</th>
                            <th>Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estadisticasMateria as $materia)
                            <tr>
                                <td>{{ $materia->nombre }}</td>
                                <td>{{ $materia->total_notas }}</td>
                                <td>{{ number_format((float) $materia->promedio, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">Sin datos de notas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Resumen por carrera</h5>
            </div>
            <div class="card-body">
                {{-- CU24: resumen real por carrera para postulantes y admitidos. --}}
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Carrera</th>
                            <th>Postulantes 1ra opcion</th>
                            <th>Admitidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resumenCarreras as $carrera)
                            <tr>
                                <td>{{ $carrera->nombre }}</td>
                                <td>{{ $carrera->postulantes_opcion_primera_count }}</td>
                                <td>{{ $carrera->admitidos_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted text-center">Sin carreras registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <a href="{{ route('reportes.index') }}" class="btn btn-primary">
            <i class="bi bi-file-earmark-bar-graph"></i> Ver reportes
        </a>
    </div>
</div>
@endsection
