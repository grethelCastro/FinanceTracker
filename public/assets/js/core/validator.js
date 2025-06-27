class FinanceValidator {
    static validateTransaction(data) {
        const errors = {};
        
        if (!data.amount || isNaN(data.amount)) {
            errors.amount = 'El monto debe ser un número válido';
        } else if (parseFloat(data.amount) <= 0) {
            errors.amount = 'El monto debe ser mayor que cero';
        }
        
        if (!data.categoryId) {
            errors.category = 'Debe seleccionar una categoría';
        }
        
        if (!data.date) {
            errors.date = 'La fecha es requerida';
        } else if (new Date(data.date) > new Date()) {
            errors.date = 'La fecha no puede ser futura';
        }
        
        return {
            isValid: Object.keys(errors).length === 0,
            errors
        };
    }
    
    static validateUserSettings(data) {
        const errors = {};
        
        if (!data.currency) {
            errors.currency = 'La moneda es requerida';
        }
        
        if (!data.date_format) {
            errors.date_format = 'El formato de fecha es requerido';
        }
        
        return {
            isValid: Object.keys(errors).length === 0,
            errors
        };
    }
    
    static formatErrorMessages(errors) {
        return Object.values(errors).join('\n');
    }
}

export default FinanceValidator;