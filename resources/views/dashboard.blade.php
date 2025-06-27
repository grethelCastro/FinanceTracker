@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Resumen Financiero</h1>
        
        <div class="row">
            @include('components.dashboard.stats-widget', [
                'title' => 'Balance Actual',
                'value' => 'C$ 12,450.00',
                'icon' => 'wallet',
                'color' => 'success'
            ])
            
            @include('components.dashboard.stats-widget', [
                'title' => 'Ingresos Mensuales',
                'value' => 'C$ 25,000.00',
                'icon' => 'arrow-down-circle',
                'color' => 'info'
            ])
            
            @include('components.dashboard.stats-widget', [
                'title' => 'Gastos Mensuales',
                'value' => 'C$ 12,550.00',
                'icon' => 'arrow-up-circle',
                'color' => 'danger'
            ])
            
            @include('components.dashboard.stats-widget', [
                'title' => 'Ahorro Mensual',
                'value' => 'C$ 12,450.00',
                'icon' => 'piggy-bank',
                'color' => 'warning'
            ])
        </div>
        
        <div class="row mt-4">
            <div class="col-md-8">
                @include('components.reports.monthly-summary')
            </div>
            <div class="col-md-4">
                @include('components.reports.expense-chart')
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                @include('components.dashboard.recent-transactions')
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="{{ asset('assets/js/modules/transactions.js') }}"></script>
        <script src="{{ asset('assets/js/modules/reports.js') }}"></script>
    @endsection
@endsection