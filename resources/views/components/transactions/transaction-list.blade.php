<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Historial de Transacciones</h5>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                <i class="bi bi-funnel"></i> Filtros
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item filter-option" href="#" data-filter="all">Todas</a></li>
                <li><a class="dropdown-item filter-option" href="#" data-filter="income">Ingresos</a></li>
                <li><a class="dropdown-item filter-option" href="#" data-filter="expense">Gastos</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item filter-option" href="#" data-filter="this_month">Este mes</a></li>
                <li><a class="dropdown-item filter-option" href="#" data-filter="last_month">Mes pasado</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="transactionsTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th class="text-end">Monto</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="transactionsTableBody">
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay transacciones aún</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>