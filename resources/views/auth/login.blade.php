@extends('layouts.app')
<!-- Vista de CU1: Iniciar sesion. Presenta el formulario de autenticacion. -->

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
                                <label for="correo" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo Electrónico
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control @error('correo') is-invalid @enderror" 
                                    id="correo" 
                                    name="correo" 
                                    value="{{ old('correo') }}"
                                    placeholder="Ingrese su correo electrónico"
                                    required
                                    autofocus
                                >
                                @error('correo')
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

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('password.reset') }}" class="text-decoration-none">
                                <small>¿Olvidaste tu contraseña?</small>
                            </a>
                        </div>
                        </form>

                        <hr class="my-4 d-none" hidden>
                        
                        <div class="alert alert-info small d-none" role="alert" hidden>
                            <i class="bi bi-info-circle"></i> 
                            <strong>Cuenta de prueba:</strong><br>
                            Correo: admin@cupficct.com<br>
                            Contraseña: password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
