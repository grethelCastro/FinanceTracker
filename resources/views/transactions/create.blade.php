@extends('layouts.app')

@section('title', isset($transaction) ? 'Editar Transacción' : 'Nueva Transacción')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            @include('transactions.transaction-form', [
                'action' => isset($transaction) 
                    ? route('transacciones.update', $transaction) 
                    : route('transacciones.store'),
                'method' => isset($transaction) ? 'PUT' : 'POST',
                'transaction' => $transaction ?? null,
                'categories' => $categories,
                'accounts' => $accounts,
                'editMode' => isset($transaction)
            ])
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrado de categorías por tipo
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category_id');
    
    function filterCategories() {
        const selectedType = typeSelect.value;
        Array.from(categorySelect.options).forEach(option => {
            if (option.value === '') return;
            option.hidden = selectedType && option.dataset.type !== selectedType;
        });
        if (categorySelect.value && categorySelect.options[categorySelect.selectedIndex].hidden) {
            categorySelect.value = '';
        }
    }
    
    typeSelect.addEventListener('change', filterCategories);
    filterCategories(); // Aplicar filtro al cargar
    
    // Inicializar datepicker
    const dateInput = document.getElementById('date');
    if (!dateInput.value) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection