@extends('layouts.guest')
@section('title', 'Iniciar Sesión')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h4 class="text-center mb-4 fw-bold">Iniciar Sesión</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 input-group">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" class="form-control form-control-lg" id="email" name="email" required autofocus placeholder="ejemplo@correo.com">
            </div>

            <div class="mb-3 input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
            </div>

            <div class="text-center text-muted">
                ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-decoration-none">Regístrate aquí</a>
            </div>
        </form>
    </div>
</div>
@endsection