import financeStorage from '../core/storage.js';

document.addEventListener('DOMContentLoaded', function() {
    // Solo ejecutar si estamos en la página de reportes
    if (!document.getElementById('categoryChart')) return;
    
    // Obtener datos del almacenamiento
    const transactions = financeStorage.getTransactions();
    const categories = financeStorage.getCategories();
    
    // Generar reporte mensual
    generateMonthlyReport(transactions);
    
    // Generar gráfico de categorías
    generateCategoryChart(transactions, categories);
    
    // Función para generar el reporte mensual
    function generateMonthlyReport(transactions) {
        const monthlySummary = document.getElementById('monthlySummary');
        if (!monthlySummary) return;
        
        // Agrupar transacciones por mes
        const monthlyData = {};
        
        transactions.forEach(transaction => {
            const date = new Date(transaction.date);
            const monthYear = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
            
            if (!monthlyData[monthYear]) {
                monthlyData[monthYear] = {
                    income: 0,
                    expense: 0,
                    months: date.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })
                };
            }
            
            if (transaction.type === 'income') {
                monthlyData[monthYear].income += transaction.amount;
            } else {
                monthlyData[monthYear].expense += transaction.amount;
            }
        });
        
        // Ordenar por fecha (más reciente primero)
        const sortedMonths = Object.keys(monthlyData).sort().reverse();
        
        // Generar HTML del reporte
        let html = '<div class="table-responsive"><table class="table table-sm">';
        html += '<thead><tr><th>Mes</th><th class="text-end">Ingresos</th><th class="text-end">Gastos</th><th class="text-end">Balance</th></tr></thead><tbody>';
        
        sortedMonths.forEach(month => {
            const data = monthlyData[month];
            const balance = data.income - data.expense;
            
            html += `
                <tr>
                    <td>${data.months}</td>
                    <td class="text-end text-success">C$ ${data.income.toFixed(2)}</td>
                    <td class="text-end text-danger">C$ ${data.expense.toFixed(2)}</td>
                    <td class="text-end fw-bold ${balance >= 0 ? 'text-success' : 'text-danger'}">C$ ${balance.toFixed(2)}</td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        monthlySummary.innerHTML = html;
    }
    
    // Función para generar el gráfico de categorías
    function generateCategoryChart(transactions, categories) {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        // Agrupar gastos por categoría
        const expenseCategories = categories.filter(c => c.type === 'expense');
        const categoryData = {};
        
        expenseCategories.forEach(category => {
            categoryData[category.name] = 0;
        });
        
        transactions.forEach(transaction => {
            if (transaction.type === 'expense') {
                const category = categories.find(c => c.id === transaction.categoryId);
                if (category) {
                    categoryData[category.name] += transaction.amount;
                }
            }
        });
        
        // Filtrar categorías sin gastos
        const labels = [];
        const data = [];
        const backgroundColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#8AC24A', '#607D8B', '#E91E63', '#9C27B0'
        ];
        
        Object.entries(categoryData).forEach(([category, amount], index) => {
            if (amount > 0) {
                labels.push(category);
                data.push(amount);
            }
        });
        
        // Crear el gráfico
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: C$ ${value.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});