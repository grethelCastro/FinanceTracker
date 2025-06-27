<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Transacciones Recientes</h5>
            <a href="{{ route('transacciones.index') }}" class="btn btn-sm btn-outline-primary">Ver Todas</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th class="text-end">Monto</th>
                    </tr>
                </thead>
                <tbody id="recentTransactions">
                    <!-- Datos se cargarán via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>