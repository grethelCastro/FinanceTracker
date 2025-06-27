import financeStorage from '../core/storage.js';

document.addEventListener('DOMContentLoaded', function() {
    const transactionForm = document.getElementById('transactionForm');
    const transactionsTable = document.getElementById('transactionsTable');
    const recentTransactions = document.getElementById('recentTransactions');
    const cancelEditBtn = document.getElementById('cancelEdit');
    
    // Cargar categorías en el formulario
    loadCategories();
    
    // Cargar transacciones
    loadTransactions();
    loadRecentTransactions();
    
    // Manejar envío del formulario
    if (transactionForm) {
        transactionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const transaction = {
                type: document.getElementById('transactionType').value,
                amount: parseFloat(document.getElementById('transactionAmount').value),
                categoryId: parseInt(document.getElementById('transactionCategory').value),
                date: document.getElementById('transactionDate').value,
                description: document.getElementById('transactionDescription').value
            };
            
            const transactionId = document.getElementById('transactionId')?.value;
            
            if (transactionId) {
                // Editar transacción existente
                if (financeStorage.updateTransaction(parseInt(transactionId), transaction)) {
                    showAlert('Transacción actualizada correctamente', 'success');
                    resetForm();
                    loadTransactions();
                    loadRecentTransactions();
                }
            } else {
                // Agregar nueva transacción
                financeStorage.addTransaction(transaction);
                showAlert('Transacción agregada correctamente', 'success');
                resetForm();
                loadTransactions();
                loadRecentTransactions();
            }
        });
    }
    
    // Manejar cancelar edición
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', resetForm);
    }
    
    // Manejar filtros
    document.querySelectorAll('.filter-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            loadTransactions(this.dataset.filter);
        });
    });
    
    // Función para cargar categorías en el select
    function loadCategories() {
        const categorySelect = document.getElementById('transactionCategory');
        if (!categorySelect) return;
        
        const categories = financeStorage.getCategories();
        categorySelect.innerHTML = '<option value="">Seleccione una categoría</option>';
        
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    }
    
    // Función para cargar transacciones en la tabla
    function loadTransactions(filter = 'all') {
        if (!transactionsTable) return;
        
        let transactions = financeStorage.getTransactions();
        
        // Aplicar filtros
        if (filter === 'income') {
            transactions = transactions.filter(t => t.type === 'income');
        } else if (filter === 'expense') {
            transactions = transactions.filter(t => t.type === 'expense');
        } else if (filter === 'this_month') {
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            transactions = transactions.filter(t => {
                const date = new Date(t.date);
                return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
            });
        } else if (filter === 'last_month') {
            const now = new Date();
            now.setMonth(now.getMonth() - 1);
            const lastMonth = now.getMonth();
            const lastYear = now.getFullYear();
            transactions = transactions.filter(t => {
                const date = new Date(t.date);
                return date.getMonth() === lastMonth && date.getFullYear() === lastYear;
            });
        }
        
        // Ordenar por fecha (más reciente primero)
        transactions.sort((a, b) => new Date(b.date) - new Date(a.date));
        
        const tbody = transactionsTable.querySelector('tbody');
        tbody.innerHTML = '';
        
        if (transactions.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="5" class="text-center py-4 text-muted">No hay transacciones registradas</td>`;
            tbody.appendChild(row);
            return;
        }
        
        const categories = financeStorage.getCategories();
        
        transactions.forEach(transaction => {
            const category = categories.find(c => c.id === transaction.categoryId);
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${formatDate(transaction.date)}</td>
                <td>${transaction.description || 'Sin descripción'}</td>
                <td><span class="badge bg-${category.type === 'income' ? 'success' : 'danger'}-light text-${category.type === 'income' ? 'success' : 'danger'}">${category.name}</span></td>
                <td class="text-end fw-bold ${category.type === 'income' ? 'text-success' : 'text-danger'}">${category.type === 'income' ? '+' : '-'}C$ ${transaction.amount.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${transaction.id}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${transaction.id}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
        });
        
        // Agregar event listeners a los botones de editar y eliminar
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                editTransaction(parseInt(this.dataset.id));
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteTransaction(parseInt(this.dataset.id));
            });
        });
    }
    
    // Función para cargar transacciones recientes
    function loadRecentTransactions() {
        if (!recentTransactions) return;
        
        const transactions = financeStorage.getTransactions()
            .sort((a, b) => new Date(b.date) - new Date(a.date))
            .slice(0, 5);
        
        const categories = financeStorage.getCategories();
        recentTransactions.innerHTML = '';
        
        if (transactions.length === 0) {
            recentTransactions.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted">No hay transacciones recientes</td></tr>`;
            return;
        }
        
        transactions.forEach(transaction => {
            const category = categories.find(c => c.id === transaction.categoryId);
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${formatDate(transaction.date)}</td>
                <td>${transaction.description || 'Sin descripción'}</td>
                <td>${category.name}</td>
                <td class="text-end fw-bold ${category.type === 'income' ? 'text-success' : 'text-danger'}">${category.type === 'income' ? '+' : '-'}C$ ${transaction.amount.toFixed(2)}</td>
            `;
            
            recentTransactions.appendChild(row);
        });
    }
    
    // Función para editar transacción
    function editTransaction(id) {
        const transaction = financeStorage.getTransactions().find(t => t.id === id);
        if (!transaction) return;
        
        document.getElementById('transactionId').value = transaction.id;
        document.getElementById('transactionType').value = transaction.type;
        document.getElementById('transactionAmount').value = transaction.amount;
        document.getElementById('transactionCategory').value = transaction.categoryId;
        document.getElementById('transactionDate').value = transaction.date;
        document.getElementById('transactionDescription').value = transaction.description || '';
        
        // Scroll al formulario
        document.getElementById('transactionForm').scrollIntoView({ behavior: 'smooth' });
    }
    
    // Función para eliminar transacción
    function deleteTransaction(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta transacción?')) {
            if (financeStorage.deleteTransaction(id)) {
                showAlert('Transacción eliminada correctamente', 'success');
                loadTransactions();
                loadRecentTransactions();
            }
        }
    }
    
    // Función para resetear el formulario
    function resetForm() {
        if (!transactionForm) return;
        
        document.getElementById('transactionId').value = '';
        document.getElementById('transactionType').value = 'income';
        document.getElementById('transactionAmount').value = '';
        document.getElementById('transactionCategory').value = '';
        document.getElementById('transactionDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('transactionDescription').value = '';
    }
    
    // Función para formatear fecha
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-ES', options);
    }
    
    // Función para mostrar alertas
    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alert.style.zIndex = '1100';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 3000);
    }
    
    // Establecer fecha actual por defecto
    if (document.getElementById('transactionDate')) {
        document.getElementById('transactionDate').value = new Date().toISOString().split('T')[0];
    }
});