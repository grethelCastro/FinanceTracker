@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Configuración de Perfil</h1>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Información Personal</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', auth()->user()->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual (para cambiar contraseña)</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Preferencias</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil.preferences.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="currency" class="form-label">Moneda Principal</label>
                            <select class="form-select @error('currency') is-invalid @enderror" 
                                    id="currency" name="currency" required>
                                <option value="NIO" {{ auth()->user()->currency === 'NIO' ? 'selected' : '' }}>Córdobas (NIO)</option>
                                <option value="USD" {{ auth()->user()->currency === 'USD' ? 'selected' : '' }}>Dólares (USD)</option>
                                <option value="EUR" {{ auth()->user()->currency === 'EUR' ? 'selected' : '' }}>Euros (EUR)</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" 
                                   id="dark_mode" name="dark_mode" 
                                   {{ auth()->user()->dark_mode ? 'checked' : '' }}>
                            <label class="form-check-label" for="dark_mode">Modo Oscuro</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Preferencias</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection