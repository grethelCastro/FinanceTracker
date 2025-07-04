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
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Listado de Transacciones</h5>
                        <a href="{{ route('transacciones.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nueva Transacción
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($transactions->isEmpty())
                        <div class="alert alert-info">No hay transacciones registradas</div>
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
                                            <td>{{ $transaction->description ?? 'Sin descripción' }}</td>
                                            <td>{{ $transaction->category->name }}</td>
                                            <td>{{ $transaction->account->name }}</td>
                                            <td class="text-end fw-bold {{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }} C$ {{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('transacciones.edit', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('transacciones.destroy', $transaction) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta transacción?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection