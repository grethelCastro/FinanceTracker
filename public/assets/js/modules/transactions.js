document.addEventListener("DOMContentLoaded", function () {
    const transactionList = document.getElementById("transactionsTableBody");

    // Datos simulados (pueden venir de una API REST)
    const transactions = [
        { date: "2024-06-10", description: "Pago de salario", category: "Salario", amount: "2000.00", type: "income" },
        { date: "2024-06-09", description: "Compra de comida", category: "Alimentos", amount: "200.00", type: "expense" }
    ];

    if (!transactions.length) {
        transactionList.innerHTML = `
            <tr><td colspan="5" class="text-center text-muted">No hay transacciones</td></tr>`;
        return;
    }

    transactions.forEach(t => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${t.date}</td>
            <td>${t.description || '-'}</td>
            <td>${t.category}</td>
            <td class="text-end ${t.type === 'income' ? 'text-success' : 'text-danger'}">
                ${t.type === 'income' ? '+' : '-'} NIO ${parseFloat(t.amount).toFixed(2)}
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-warning edit-btn" data-id="${t.id}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${t.id}"><i class="bi bi-trash"></i></button>
            </td>
        `;
        transactionList.appendChild(row);
    });
});