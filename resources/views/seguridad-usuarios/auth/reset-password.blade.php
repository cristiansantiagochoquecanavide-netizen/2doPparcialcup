@extends('layouts.app')
<!-- Vista para solicitar recuperación de contraseña -->

@section('titulo', 'Recuperar Contraseña')

@section('contenido')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-key" style="font-size: 3rem; color: #3498db;"></i>
                            <h3 class="mt-3">Recuperar Contraseña</h3>
                            <p class="text-muted">Ingresa tu correo para recibir un enlace de recuperación</p>
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

                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-check-circle"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="correo" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo Electrónico
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control @error('correo') is-invalid @enderror" 
                                    id="correo" 
                                    name="correo" 
                                    value="{{ old('correo') }}"
                                    placeholder="Ingresa tu correo electrónico"
                                    required
                                    autofocus
                                >
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-envelope"></i> Enviar Enlace de Recuperación
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                ¿Recuerdas tu contraseña?
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
