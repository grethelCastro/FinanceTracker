<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            {{ $editMode ? 'Editar Transacción' : 'Nueva Transacción' }}
        </h5>
    </div>
    
    <div class="card-body">
        <form action="{{ $action }}" method="POST">
            @csrf
            @method($method ?? 'POST')
            
            <div class="mb-3">
                <label for="type" class="form-label">Tipo *</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Seleccionar tipo</option>
                    <option value="income" {{ old('type', $transaction->type ?? '') === 'income' ? 'selected' : '' }}>Ingreso</option>
                    <option value="expense" {{ old('type', $transaction->type ?? '') === 'expense' ? 'selected' : '' }}>Gasto</option>
                </select>
                @error('type')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Monto ({{ auth()->user()->currency ?? 'NIO' }}) *</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                       value="{{ old('amount', $transaction->amount ?? '') }}" required>
                @error('amount')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Categoría *</label>
                <div class="input-group">
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    data-type="{{ $category->type }}"
                                    {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ ucfirst($category->type) }})
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
                @error('category_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

<div class="mb-3">
    <label for="account_id" class="form-label">Cuenta *</label>
    <div class="input-group">
        <select class="form-select" id="account_id" name="account_id" required>
            <option value="">Seleccionar cuenta</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">
                    {{ $account->name }} ({{ ucfirst($account->type) }} - {{ $account->balance }} {{ auth()->user()->currency ?? 'NIO' }})
                </option>
            @endforeach
        </select>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newAccountModal">
            <i class="bi bi-plus"></i>
        </button>
    </div>
</div>


            <div class="mb-3">
                <label for="date" class="form-label">Fecha *</label>
                <input type="date" class="form-control" id="date" name="date" 
                       value="{{ old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : '') }}" required>
                @error('date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $transaction->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    {{ $editMode ? 'Actualizar Transacción' : 'Guardar Transacción' }}
                </button>
            </div>
        </form>
    </div>
</div>

@include('transactions.partials.account-modal')

@include('transactions.partials.category-modal')