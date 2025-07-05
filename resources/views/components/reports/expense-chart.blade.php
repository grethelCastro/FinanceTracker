<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Distribución de Gastos</h5>
    </div>
    <div class="card-body">
        <canvas id="expenseChart" height="300"></canvas>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('expenseChart').getContext('2d');
    
    // Aquí puedes agregar lógica para obtener datos de gastos por categoría
    // Por ahora usaremos datos de ejemplo
    const categories = ['Comida', 'Transporte', 'Entretenimiento', 'Servicios'];
    const amounts = [1200, 800, 500, 700];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categories,
            datasets: [{
                data: amounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': C$ ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection