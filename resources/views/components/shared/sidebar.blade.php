<aside class="app-sidebar">
    <div class="sidebar-header p-3">
        <h5 class="sidebar-title mb-0">FinanceTracker</h5>
        <button class="btn-close sidebar-close d-lg-none"></button>
    </div>
    
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('transacciones*') ? 'active' : '' }}" href="{{ route('transacciones.index') }}">
                <i class="bi bi-arrow-left-right me-2"></i>Transacciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('reportes') ? 'active' : '' }}" href="{{ route('reportes') }}">
                <i class="bi bi-graph-up me-2"></i>Reportes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('perfil') ? 'active' : '' }}" href="{{ route('perfil') }}">
                <i class="bi bi-person me-2"></i>Perfil
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer p-3">
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