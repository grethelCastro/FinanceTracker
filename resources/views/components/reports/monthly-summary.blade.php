<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Resumen Mensual</h5>
    </div>
    <div class="card-body">
        <canvas id="monthlySummaryChart" height="300"></canvas>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlySummaryChart').getContext('2d');
    const months = @json(array_keys($monthlySummary));
    const incomeData = @json(array_column($monthlySummary, 'income'));
    const expensesData = @json(array_column($monthlySummary, 'expenses'));
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Ingresos',
                    data: incomeData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Gastos',
                    data: expensesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
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
                            return context.dataset.label + ': C$ ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection