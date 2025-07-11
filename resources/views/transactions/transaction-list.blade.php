<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-2 mb-md-0">Historial de Transacciones</h5>
            
            <div class="d-flex gap-2 flex-wrap">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel"></i> Filtros
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Tipo</h6></li>
                        <li><a class="dropdown-item" href="#" data-filter="type" data-value="all">Todos</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="type" data-value="income">Ingresos</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="type" data-value="expense">Gastos</a></li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Cuenta</h6></li>
                        @foreach($accounts as $account)
                            <li><a class="dropdown-item" href="#" data-filter="account_id" data-value="{{ $account->id }}">
                                {{ $account->name }}
                            </a></li>
                        @endforeach
                        
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Categoría</h6></li>
                        @foreach($categories as $category)
                            <li><a class="dropdown-item" href="#" data-filter="category_id" data-value="{{ $category->id }}">
                                {{ $category->name }}
                            </a></li>
                        @endforeach
                    </ul>
                </div>
                
                <button id="clearFilters" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
                
                <a href="{{ route('transacciones.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nueva
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        @if(request()->anyFilled(['type', 'account_id', 'category_id']))
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <small class="text-muted">Filtros aplicados:</small>
                    @if(request('type') && request('type') != 'all')
                        <span class="badge bg-primary">
                            Tipo: {{ request('type') == 'income' ? 'Ingresos' : 'Gastos' }}
                            <a href="?{{ http_build_query(request()->except('type')) }}" class="text-white ms-2">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('account_id'))
                        <span class="badge bg-primary">
                            Cuenta: {{ $accounts->firstWhere('id', request('account_id'))->name }}
                            <a href="?{{ http_build_query(request()->except('account_id')) }}" class="text-white ms-2">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('category_id'))
                        <span class="badge bg-primary">
                            Categoría: {{ $categories->firstWhere('id', request('category_id'))->name }}
                            <a href="?{{ http_build_query(request()->except('category_id')) }}" class="text-white ms-2">
                                <i class="bi bi-x"></i>
                            </a>
                        </span>
                    @endif
                </div>
            </div>
        @endif
        
        @if($transactions->isEmpty())
            <div class="alert alert-info">No hay transacciones con los filtros aplicados</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Cuenta</th>
                            <th class="text-end">Monto</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                                <td>
                                    @if($transaction->description)
                                        <span data-bs-toggle="tooltip" title="{{ $transaction->description }}">
                                            {{ Str::limit($transaction->description, 25) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Sin descripción</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->category->type === 'income' ? 'success' : 'danger' }}-subtle text-{{ $transaction->category->type === 'income' ? 'success' : 'danger' }}">
                                        {{ $transaction->category->name }}
                                    </span>
                                </td>
                                <td>{{ $transaction->account->name }}</td>
                                <td class="text-end fw-bold {{ $transaction->category->type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->category->type === 'income' ? '+' : '-' }} 
                                    {{ number_format($transaction->amount, 2) }} {{ auth()->user()->currency ?? 'NIO' }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('transacciones.edit', $transaction) }}" 
                                           class="btn btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('transacciones.destroy', $transaction) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Eliminar esta transacción?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-center">
                {{ $transactions->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            
            @section('styles')
            <style>
                /* Estilos compactos para paginación */
                .pagination {
                    --bs-pagination-padding-x: 0.5rem;
                    --bs-pagination-padding-y: 0.25rem;
                    --bs-pagination-font-size: 0.875rem;
                    --bs-pagination-color: var(--bs-secondary);
                    --bs-pagination-hover-color: var(--bs-secondary);
                    --bs-pagination-focus-color: var(--bs-secondary);
                    --bs-pagination-active-bg: var(--bs-secondary);
                    --bs-pagination-active-border-color: var(--bs-secondary);
                }
                
                .page-link {
                    border-radius: 0.25rem;
                    margin: 0 0.15rem;
                }
            </style>
            @endsection
        @endif
    </div>
</div>