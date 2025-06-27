<aside class="app-sidebar">
    <div class="sidebar-header">
        <h5 class="sidebar-title">Menú Principal</h5>
        <button class="sidebar-close d-lg-none">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('transacciones.index') }}">
                <i class="bi bi-arrow-left-right"></i>Transacciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('reportes') }}">
                <i class="bi bi-graph-up"></i>Reportes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('perfil') }}">
                <i class="bi bi-person"></i>Perfil
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer mt-auto pt-3">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" type="button" id="currencyDropdown" data-bs-toggle="dropdown">
                <i class="bi bi-currency-exchange me-1"></i>
                Moneda: Córdobas (NIO)
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Córdobas (NIO)</a></li>
                <li><a class="dropdown-item" href="#">Dólares (USD)</a></li>
            </ul>
        </div>
    </div>
</aside>