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
            @include('components.reports.monthly-summary', ['monthlySummary' => $monthlySummary])
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
</div>
<br>
<br>
<br>
<br>
<br>
<br>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de categorías
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryLabels = @json($categoryLabels);
    const categoryAmounts = @json($categoryAmounts);
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Gastos por Categoría',
                data: categoryAmounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'C$ ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Gasto: C$ ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection