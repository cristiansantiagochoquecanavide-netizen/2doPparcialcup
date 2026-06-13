@extends('layouts.app')
<!-- Vista para establecer nueva contraseña -->

@section('titulo', 'Establecer Nueva Contraseña')

@section('contenido')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-lock" style="font-size: 3rem; color: #3498db;"></i>
                            <h3 class="mt-3">Nueva Contraseña</h3>
                            <p class="text-muted">Establece una nueva contraseña segura</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-circle"></i>
                                <strong>Error:</strong>
                                @foreach($errors->all() as $error)
                                    <br>{{ $error }}
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('password.reset.post', $token) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Nueva Contraseña
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Mínimo 8 caracteres"
                                    required
                                    autofocus
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Debe tener al menos 8 caracteres
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock"></i> Confirmar Contraseña
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    placeholder="Repite tu contraseña"
                                    required
                                >
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-check-circle"></i> Cambiar Contraseña
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                <a href="{{ route('login') }}" class="text-decoration-none">Volver al login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
