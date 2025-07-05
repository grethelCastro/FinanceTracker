<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FinanceTracker - @yield('title')</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('assets/css/transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/utilities.css') }}">>
    <link rel="stylesheet" href="{{ asset('assets/css/form.css') }}"> <!-- Nuevo archivo CSS para formularios -->

    @yield('styles')
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container-fluid px-3">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/core/pageTransitions.js') }}"></script>
    <!-- JavaScript Modules -->


</body>
</html>