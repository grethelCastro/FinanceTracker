<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FinanceTracker - @yield('title')</title>
    
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transitions.css') }}">
    
    @yield('styles')
</head>
<body>
    <!-- Loader para transiciones -->
    <div class="page-loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <div class="app-container">
        @include('components.shared.sidebar')
        
        <div class="main-content">
            @include('components.shared.header')
            
            <main class="content-wrapper">
                @yield('content')
            </main>
            
            @include('components.shared.footer')
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript Core -->
    <script src="{{ asset('assets/js/core/api.js') }}"></script>
    <script src="{{ asset('assets/js/core/storage.js') }}"></script>
    <script src="{{ asset('assets/js/core/validator.js') }}"></script>
    
    <!-- JavaScript Modules -->
    <script src="{{ asset('assets/js/modules/darkMode.js') }}"></script>
    <script src="{{ asset('assets/js/modules/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/modules/pageTransitions.js') }}"></script>
    
    @yield('scripts')
</body>
</html>