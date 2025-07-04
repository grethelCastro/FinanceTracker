@extends('layouts.guest')
@section('title', 'Crear Cuenta')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h4 class="text-center mb-4 fw-bold">Crear Cuenta</h4>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3 input-group">
                <i class="bi bi-person-fill"></i>
                <input type="text" class="form-control form-control-lg" id="name" name="name" required autofocus placeholder="Juan Pérez">
            </div>

            <div class="mb-3 input-group">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" class="form-control form-control-lg" id="email" name="email" required placeholder="ejemplo@correo.com">
            </div>

            <div class="mb-3 input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="mb-3 input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="form-control form-control-lg" id="password-confirm" name="password_confirmation" required placeholder="••••••••">
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Registrar</button>
            </div>

            <div class="text-center text-muted">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión aquí</a>
            </div>
        </form>
    </div>
</div>
@endsection