@extends('layouts.app')

@section('title', 'Transacciones')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Transacciones</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @include('transactions.transaction-list', [
                'transactions' => $transactions,
                'accounts' => $accounts,
                'categories' => $categories
            ])
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(el => new bootstrap.Tooltip(el));

    // Manejar filtros
    document.querySelectorAll('[data-filter]').forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(window.location.href);
            url.searchParams.set(this.dataset.filter, this.dataset.value);
            window.location.href = url.toString();
        });
    });

    // Limpiar filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        window.location.href = "{{ route('transacciones.index') }}";
    });
});
</script>
@endsection