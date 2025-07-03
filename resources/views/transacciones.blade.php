@extends('layouts.app')

@section('title', 'Transacciones')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Gestión de Transacciones</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @include('components.transactions.transaction-form')
            <br>
            <br>
            <br>
        </div>
        <div class="col-md-8">
            @include('components.transactions.transaction-list')
            <br>
            <br>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('assets/js/modules/transactions.js') }}"></script>
@endsection
@endsection