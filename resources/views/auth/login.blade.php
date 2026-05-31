@extends('layouts.app')

@section('titulo', 'Iniciar Sesión')

@section('contenido')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-mortarboard" style="font-size: 3rem; color: #3498db;"></i>
                            <h3 class="mt-3">CUP FICCT</h3>
                            <p class="text-muted">Sistema Web de Admisión Universitaria</p>
                        </div>

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nombre_usuario" class="form-label">
                                    <i class="bi bi-person"></i> Usuario
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('nombre_usuario') is-invalid @enderror" 
                                    id="nombre_usuario" 
                                    name="nombre_usuario" 
                                    value="{{ old('nombre_usuario') }}"
                                    placeholder="Ingrese su usuario"
                                    required
                                    autofocus
                                >
                                @error('nombre_usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Contraseña
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Ingrese su contraseña"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <strong>Error:</strong>
                                    @foreach($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </form>

                        <hr class="my-4">
                        
                        <div class="alert alert-info small" role="alert">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Cuenta de prueba:</strong><br>
                            Usuario: admin<br>
                            Contraseña: password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
