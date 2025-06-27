<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">{{ isset($transaction) ? 'Editar Transacción' : 'Nueva Transacción' }}</h5>
    </div>
    <div class="card-body">
        <form id="transactionForm">
            <input type="hidden" id="transactionId" value="{{ $transaction->id ?? '' }}">
            
            <div class="mb-3">
                <label for="transactionType" class="form-label">Tipo</label>
                <select class="form-select" id="transactionType" required>
                    <option value="income">Ingreso</option>
                    <option value="expense">Gasto</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="transactionAmount" class="form-label">Monto (NIO)</label>
                <input type="number" class="form-control" id="transactionAmount" step="0.01" min="0" required>
            </div>
            
            <div class="mb-3">
                <label for="transactionCategory" class="form-label">Categoría</label>
                <select class="form-select" id="transactionCategory" required>
                    <option value="">Seleccione una categoría</option>
                    <!-- Categorías se cargarán via JavaScript -->
                </select>
            </div>
            
            <div class="mb-3">
                <label for="transactionDate" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="transactionDate" required>
            </div>
            
            <div class="mb-3">
                <label for="transactionDescription" class="form-label">Descripción</label>
                <textarea class="form-control" id="transactionDescription" rows="2"></textarea>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ isset($transaction) ? 'Actualizar' : 'Guardar' }}
                </button>
                @if(isset($transaction))
                <button type="button" class="btn btn-outline-danger" id="cancelEdit">
                    Cancelar
                </button>
                @endif
            </div>
        </form>
    </div>
</div>