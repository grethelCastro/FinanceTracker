@extends('layouts.app')
@section('title', 'Nueva Transacción')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Nueva Transacción</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('transacciones.store') }}" method="POST">
                        @csrf

                        <!-- Aquí va tu formulario completo -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="income">Ingreso</option>
                                <option value="expense">Gasto</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Monto ({{ auth()->user()->currency }})</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Categoría</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach(auth()->user()->categories as $category)
                                    <option value="{{ $category->id }}" data-type="{{ $category->type }}">
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="account_id" class="form-label">Cuenta</label>
                            <select class="form-select" id="account_id" name="account_id" required>
                                <option value="">Seleccionar cuenta</option>
                                @foreach(auth()->user()->accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->name }} ({{ ucfirst($account->type) }} - {{ $account->balance }} {{ auth()->user()->currency }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="date" name="date" required 
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Transacción</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('type').addEventListener('change', function () {
        const selectedType = this.value;
        const categorySelect = document.getElementById('category_id');

        Array.from(categorySelect.options).forEach(option => {
            if (option.value === '') return;
            option.style.display = option.dataset.type === selectedType ? 'block' : 'none';
        });

        categorySelect.value = '';
    });
</script>
@endsection