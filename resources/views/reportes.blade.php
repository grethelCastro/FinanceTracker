@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Reportes Financieros</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            @include('components.reports.monthly-summary')
        </div>
        <div class="col-md-4">
            @include('components.reports.expense-chart')
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Análisis por Categoría</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div><br><br><br><br>

@section('scripts')
<script src="{{ asset('assets/js/modules/reports.js') }}"></script>
@endsection
@endsection