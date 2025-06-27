class FinanceAPI {
    constructor() {
        this.storageKey = 'financeTrackerData';
    }

    // Simular llamadas API con retraso
    async simulateDelay() {
        return new Promise(resolve => setTimeout(resolve, 500));
    }

    async getTransactions() {
        await this.simulateDelay();
        const data = JSON.parse(localStorage.getItem(this.storageKey)) || { transactions: [] };
        return data.transactions;
    }

    async addTransaction(transaction) {
        await this.simulateDelay();
        const data = JSON.parse(localStorage.getItem(this.storageKey)) || { transactions: [] };
        transaction.id = Date.now();
        data.transactions.push(transaction);
        localStorage.setItem(this.storageKey, JSON.stringify(data));
        return transaction;
    }

    async updateTransaction(id, updatedData) {
        await this.simulateDelay();
        const data = JSON.parse(localStorage.getItem(this.storageKey)) || { transactions: [] };
        const index = data.transactions.findIndex(t => t.id === id);
        
        if (index !== -1) {
            data.transactions[index] = { ...data.transactions[index], ...updatedData };
            localStorage.setItem(this.storageKey, JSON.stringify(data));
            return data.transactions[index];
        }
        
        return null;
    }

    async deleteTransaction(id) {
        await this.simulateDelay();
        const data = JSON.parse(localStorage.getItem(this.storageKey)) || { transactions: [] };
        const index = data.transactions.findIndex(t => t.id === id);
        
        if (index !== -1) {
            const deleted = data.transactions.splice(index, 1);
            localStorage.setItem(this.storageKey, JSON.stringify(data));
            return deleted[0];
        }
        
        return null;
    }

    async getUserSettings() {
        await this.simulateDelay();
        const data = JSON.parse(localStorage.getItem(this.storageKey)) || { userSettings: {} };
        return data.userSettings;
    }

    async updateUserSettings(settings) {
        await this.simulateDelay();
        const data = JSON.parse(localStorage.getItem(this.storageKey)) || { userSettings: {} };
        data.userSettings = { ...data.userSettings, ...settings };
        localStorage.setItem(this.storageKey, JSON.stringify(data));
        return data.userSettings;
    }
}

const financeAPI = new FinanceAPI();
export default financeAPI;