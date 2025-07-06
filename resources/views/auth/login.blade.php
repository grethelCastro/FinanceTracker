@extends('layouts.guest')
@section('title', 'Iniciar Sesión')

@section('content')
<div class="auth-container">
    <form class="modern-form" method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-title">Iniciar Sesión</div>

        <div class="form-body">
            <!-- Campo Email -->
            <div class="input-group">
                <div class="input-wrapper">
                    <svg fill="none" viewBox="0 0 24 24" class="input-icon">
                        <path stroke-width="1.5" stroke="currentColor" d="M3 8L10.8906 13.2604C11.5624 13.7083 12.4376 13.7083 13.1094 13.2604L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z"></path>
                    </svg>
                    <input id="email" name="email" type="email" class="form-input" placeholder="ejemplo@correo.com" required autofocus>
                </div>
            </div>

            <!-- Campo Contraseña -->
            <div class="input-group">
                <div class="input-wrapper">
                    <svg fill="none" viewBox="0 0 24 24" class="input-icon">
                        <path stroke-width="1.5" stroke="currentColor" d="M12 10V14M8 6H16C17.1046 6 18 6.89543 18 8V16C18 17.1046 17.1046 18 16 18H8C6.89543 18 6 17.1046 6 16V8C6 6.89543 6.89543 6 8 6Z"></path>
                    </svg>
                    <input id="password" name="password" type="password" class="form-input" placeholder="••••••••" required>
                    <button class="password-toggle" type="button" aria-label="Mostrar contraseña">
                        <svg fill="none" viewBox="0 0 24 24" class="eye-icon">
                            <path stroke-width="1.5" stroke="currentColor" d="M2 12C2 12 5 5 12 5C19 5 22 12 22 12C22 12 19 19 12 19C5 19 2 12 2 12Z"></path>
                            <circle stroke-width="1.5" stroke="currentColor" r="3" cy="12" cx="12"></circle>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <button class="submit-button" type="submit">
            <span class="button-text">Iniciar Sesión</span>
            <div class="button-glow"></div>
        </button>

        <div class="form-footer">
            <a class="login-link" href="{{ route('register') }}">
                ¿No tienes cuenta? <span>Regístrate aquí</span>
            </a>
        </div>
    </form>
</div>
@endsection