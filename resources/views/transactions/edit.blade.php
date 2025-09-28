@extends('layouts.app')

@section('title', 'Editar Transacción')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Editar Transacción</h1>
                <a href="{{ route('transacciones.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario de edición -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('transacciones.update', $transaction) }}" method="POST" id="editTransactionForm">
                        @csrf
                        @method('PUT')

                        <!-- Tipo de Transacción -->
                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">Tipo de Transacción</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="income" value="income" 
                                    {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="income">
                                    <i class="bi bi-arrow-up"></i> Ingreso
                                </label>

                                <input type="radio" class="btn-check" name="type" id="expense" value="expense"
                                    {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="expense">
                                    <i class="bi bi-arrow-down"></i> Gasto
                                </label>
                            </div>
                            @error('type')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Monto -->
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-semibold">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" 
                                       name="amount" 
                                       value="{{ old('amount', $transaction->amount) }}" 
                                       step="0.01" 
                                       min="0.01" 
                                       max="9999999.99"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('amount')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-semibold">Categoría</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cuenta -->
                        <div class="mb-4">
                            <label for="account_id" class="form-label fw-semibold">Cuenta</label>
                            <select class="form-select @error('account_id') is-invalid @enderror" 
                                    id="account_id" 
                                    name="account_id" 
                                    required>
                                <option value="">Seleccionar cuenta</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" 
                                        {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }} - ${{ number_format($account->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha -->
                        <div class="mb-4">
                            <label for="date" class="form-label fw-semibold">Fecha</label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $transaction->date->format('Y-m-d')) }}" 
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('date')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Descripción (Opcional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Agregar una descripción...">{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('transacciones.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-check"></i> Actualizar Transacción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editTransactionForm');
    const submitBtn = document.getElementById('submitBtn');

    // Validación en tiempo real para el monto
    const amountInput = document.getElementById('amount');
    amountInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < 0.01) {
            this.setCustomValidity('El monto debe ser mayor a 0');
        } else if (value > 9999999.99) {
            this.setCustomValidity('El monto no puede exceder 9,999,999.99');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validación de fecha
    const dateInput = document.getElementById('date');
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(23, 59, 59, 999);

        if (selectedDate > today) {
            this.setCustomValidity('La fecha no puede ser futura');
        } else {
            this.setCustomValidity('');
        }
    });

    // Manejar envío del formulario
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';
    });
});
</script>
@endsection