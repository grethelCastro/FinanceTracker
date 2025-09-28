@extends('layouts.app')

@section('title', 'Detalles de Transacción')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Detalles de Transacción</h1>
                    <p class="text-muted mb-0">Información completa de la transacción</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('transacciones.edit', $transaction->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-2"></i>Editar
                    </a>
                    <a href="{{ route('transacciones.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Tarjeta Principal -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <div class="text-center mb-3">
                        <div class="mb-3">
                            <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }} fs-6 px-3 py-2">
                                <i class="bi bi-{{ $transaction->type === 'income' ? 'arrow-down-left' : 'arrow-up-right' }} me-2"></i>
                                {{ $transaction->type === 'income' ? 'INGRESO' : 'GASTO' }}
                            </span>
                        </div>
                        <h2 class="{{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }} mb-2">
                            {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                        </h2>
                        <p class="text-muted">{{ $transaction->date->format('d F, Y') }}</p>
                    </div>
                </div>
                
                <div class="card-body pt-0">
                    <!-- Descripción -->
                    @if($transaction->description)
                    <div class="alert alert-light border mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-chat-text text-primary fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Descripción</h6>
                                <p class="mb-0">{{ $transaction->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Información Detallada -->
                    <div class="row g-3">
                        <!-- Categoría -->
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-{{ $transaction->category->type === 'income' ? 'success' : 'danger' }}-subtle text-{{ $transaction->category->type === 'income' ? 'success' : 'danger' }} rounded p-3">
                                                <i class="bi bi-tag fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-muted mb-1">Categoría</h6>
                                            <p class="card-text fw-bold mb-0">{{ $transaction->category->name ?? 'Sin categoría' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cuenta -->
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary-subtle text-primary rounded p-3">
                                                <i class="bi bi-wallet fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-muted mb-1">Cuenta</h6>
                                            <p class="card-text fw-bold mb-0">{{ $transaction->account->name ?? 'Sin cuenta' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha de Transacción -->
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-info-subtle text-info rounded p-3">
                                                <i class="bi bi-calendar-event fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-muted mb-1">Fecha de Transacción</h6>
                                            <p class="card-text fw-bold mb-0">{{ $transaction->date->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-secondary-subtle text-secondary rounded p-3">
                                                <i class="bi bi-clock-history fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-muted mb-1">Estado</h6>
                                            <p class="card-text fw-bold mb-0">
                                                <span class="badge bg-success">Completada</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Auditoría -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted mb-3">Información de Auditoría</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    <small>Creado: {{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    <small>Actualizado: {{ $transaction->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer con Acciones -->
                <div class="card-footer bg-transparent border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <small>ID: #{{ $transaction->id }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('transacciones.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger" 
                                        onclick="return confirm('¿Estás seguro de eliminar esta transacción? Esta acción no se puede deshacer.')">
                                    <i class="bi bi-trash me-2"></i>Eliminar
                                </button>
                            </form>
                            <a href="{{ route('transacciones.edit', $transaction->id) }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square me-2"></i>Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Resumen Rápido -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Resumen Rápido
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="{{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }} mb-1">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                </h5>
                                <small class="text-muted">Monto</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-primary mb-1">
                                    {{ $transaction->category->type === 'income' ? 'Ingreso' : 'Gasto' }}
                                </h5>
                                <small class="text-muted">Tipo</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <h5 class="text-info mb-1">
                                    {{ $transaction->date->format('d/m') }}
                                </h5>
                                <small class="text-muted">Fecha</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    // Efecto de hover en tarjetas
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.2s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<style>
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.badge {
    font-size: 0.75em;
    padding: 0.5em 1em;
}

.bg-success-subtle {
    background-color: rgba(var(--success-rgb), 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(var(--danger-rgb), 0.1) !important;
}

.bg-primary-subtle {
    background-color: rgba(var(--primary-rgb), 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(var(--info-rgb), 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(var(--secondary-rgb), 0.1) !important;
}

.alert-light {
    background-color: rgba(var(--light-rgb), 0.5);
    border: 1px solid rgba(var(--border-color-rgb), 0.2);
}

/* Efectos de hover mejorados */
.card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body .row.g-3 .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .card-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-footer .btn {
        width: 100%;
    }
}
</style>
@endsection