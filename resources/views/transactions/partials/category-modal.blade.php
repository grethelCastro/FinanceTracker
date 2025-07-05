<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="newCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="from_transaction" value="1">
                    <div class="mb-3">
                        <label for="new_category_name" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="new_category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo *</label>
                        <select class="form-select" name="type" required>
                            <option value="income">Ingreso</option>
                            <option value="expense">Gasto</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newCategoryForm = document.getElementById('newCategoryForm');
    if (!newCategoryForm) return;

    newCategoryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: document.getElementById('new_category_name').value,
            type: document.querySelector('#newCategoryForm select[name="type"]').value,
            from_transaction: true
        };

        fetch("{{ route('categories.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta');
            return response.json();
        })
        .then(data => {
            // Agregar nueva categoría al select
            const select = document.getElementById('category_id');
            const option = new Option(
                `${data.name} (${data.type === 'income' ? 'Ingreso' : 'Gasto'})`, 
                data.id
            );
            option.dataset.type = data.type;
            select.add(option);
            select.value = data.id;
            
            // Cerrar modal y resetear
            bootstrap.Modal.getInstance(document.getElementById('newCategoryModal')).hide();
            newCategoryForm.reset();
            
            // Mostrar mensaje de éxito
            alert('Categoría creada exitosamente');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al crear la categoría');
        });
    });
});
</script>