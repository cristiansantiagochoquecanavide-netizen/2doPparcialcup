@extends('layouts.app')
<!-- Vista de CU12: Gestionar grupos. Calcula la cantidad estimada de grupos. -->

@section('titulo', 'Calcular Cantidad de Grupos')

@section('contenido')
<h1 class="page-title">
    <i class="bi bi-calculator"></i> Calcular Cantidad de Grupos
</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form id="calculatorForm" method="POST" action="{{ route('grupos.calcular') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="id_gestion" class="form-label">
                            <i class="bi bi-calendar-range"></i> Gestión/Año Académico
                        </label>
                        <select class="form-select @error('id_gestion') is-invalid @enderror" id="id_gestion" name="id_gestion" required>
                            <option value="">-- Seleccionar Gestión --</option>
                            @foreach($gestiones as $gestion)
                                <option value="{{ $gestion->id_gestion }}" @selected(old('id_gestion') == $gestion->id_gestion)>
                                    {{ $gestion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_gestion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_carrera" class="form-label">
                            <i class="bi bi-mortarboard"></i> Carrera
                        </label>
                        <select class="form-select @error('id_carrera') is-invalid @enderror" id="id_carrera" name="id_carrera" required>
                            <option value="">-- Seleccionar Carrera --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" @selected(old('id_carrera') == $carrera->id_carrera)>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_carrera')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cantidad_postulantes" class="form-label">
                            <i class="bi bi-people"></i> Cantidad de Postulantes
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="cantidad_postulantes" 
                            placeholder="Ej: 120"
                            min="1"
                        >
                        <small class="text-muted">Ingrese la cantidad estimada de postulantes</small>
                    </div>

                    <div class="mb-3">
                        <label for="cupo_grupo" class="form-label">
                            <i class="bi bi-diagram-3"></i> Cupo por Grupo
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="cupo_grupo" 
                            value="40"
                            placeholder="Ej: 40"
                            min="1"
                        >
                        <small class="text-muted">Capacidad máxima recomendada por grupo</small>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Ingrese la cantidad de postulantes estimada y el cupo máximo por grupo para calcular.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="calcularGrupos()">
                            <i class="bi bi-calculator"></i> Calcular
                        </button>
                        <a href="{{ route('grupos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Resultado del Cálculo</h5>
            </div>
            <div class="card-body">
                <div id="resultado" class="text-muted">
                    <p>Ingrese los datos y haga clic en "Calcular"</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calcularGrupos() {
    const cantidad = parseInt(document.getElementById('cantidad_postulantes').value) || 0;
    const cupo = parseInt(document.getElementById('cupo_grupo').value) || 1;
    
    if (cantidad <= 0) {
        document.getElementById('resultado').innerHTML = '<p class="text-danger">Por favor ingrese una cantidad válida</p>';
        return;
    }
    
    const gruposNecesarios = Math.ceil(cantidad / cupo);
    
    document.getElementById('resultado').innerHTML = `
        <div class="alert alert-success" role="alert">
            <p><strong>Cantidad de Postulantes:</strong> ${cantidad}</p>
            <p><strong>Cupo por Grupo:</strong> ${cupo}</p>
            <hr>
            <p><strong>Grupos Necesarios:</strong> <span class="display-4">${gruposNecesarios}</span></p>
            <small class="text-muted">Capacidad total: ${gruposNecesarios * cupo}</small>
        </div>
    `;
}
</script>
@endpush
@endsection
