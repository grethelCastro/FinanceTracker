@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Configuración de Perfil</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Información Personal</h5>
                </div>
                <div class="card-body">
                    <form id="profileForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" value="Usuario Demo">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" value="usuario@demo.com">
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
                    <form id="preferencesForm">
                        <div class="mb-3">
                            <label class="form-label">Moneda Principal</label>
                            <select class="form-select" id="currency">
                                <option value="NIO" selected>Córdobas (NIO)</option>
                                <option value="USD">Dólares (USD)</option>
                            </select>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                            <label class="form-check-label" for="darkModeSwitch">Modo Oscuro</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Preferencias</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const storage = JSON.parse(localStorage.getItem('financeTrackerData'));
        if (storage.userSettings.darkMode) {
            document.getElementById('darkModeSwitch').checked = true;
        }
    });
</script>
@endsection
@endsection