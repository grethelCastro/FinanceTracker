class FinanceStorage {
    constructor() {
        this.storageKey = 'financeTrackerData';
        this.defaultData = {
            transactions: [],
            categories: [
                { id: 1, name: 'Alimentos', type: 'expense' },
                { id: 2, name: 'Transporte', type: 'expense' },
                { id: 3, name: 'Salario', type: 'income' },
                { id: 4, name: 'Servicios', type: 'expense' },
                { id: 5, name: 'Entretenimiento', type: 'expense' }
            ],
            balance: 0,
            userSettings: {
                currency: 'NIO',
                darkMode: false
            }
        };
    }

    initialize() {
        if (!localStorage.getItem(this.storageKey)) {
            localStorage.setItem(this.storageKey, JSON.stringify(this.defaultData));
        }
    }

    getData() {
        return JSON.parse(localStorage.getItem(this.storageKey));
    }

    saveData(data) {
        localStorage.setItem(this.storageKey, JSON.stringify(data));
    }

    // Métodos específicos para transacciones
    getTransactions() {
        const data = this.getData();
        return data.transactions;
    }

    addTransaction(transaction) {
        const data = this.getData();
        transaction.id = Date.now(); // ID único basado en timestamp
        data.transactions.push(transaction);
        this.saveData(data);
        return transaction;
    }

    updateTransaction(id, updatedTransaction) {
        const data = this.getData();
        const index = data.transactions.findIndex(t => t.id === id);
        if (index !== -1) {
            data.transactions[index] = { ...data.transactions[index], ...updatedTransaction };
            this.saveData(data);
            return true;
        }
        return false;
    }

    deleteTransaction(id) {
        const data = this.getData();
        const index = data.transactions.findIndex(t => t.id === id);
        if (index !== -1) {
            data.transactions.splice(index, 1);
            this.saveData(data);
            return true;
        }
        return false;
    }

    // Métodos para categorías
    getCategories() {
        const data = this.getData();
        return data.categories;
    }

    // Métodos para configuración de usuario
    getUserSettings() {
        const data = this.getData();
        return data.userSettings;
    }

    updateUserSettings(settings) {
        const data = this.getData();
        data.userSettings = { ...data.userSettings, ...settings };
        this.saveData(data);
        return data.userSettings;
    }
}

const financeStorage = new FinanceStorage();
financeStorage.initialize();

export default financeStorage;