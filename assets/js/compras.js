document.addEventListener('DOMContentLoaded', function() {
    // Confirmar antes de eliminar un producto
    const deleteButtons = document.querySelectorAll('.btn-eliminar');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                e.preventDefault();
            }
        });
    });
    
    // Validar cantidades antes de actualizar
    const form = document.querySelector('.form-carrito');
    if (form) {
        form.addEventListener('submit', function(e) {
            const quantityInputs = document.querySelectorAll('input[name^="cantidad"]');
            let allValid = true;
            
            quantityInputs.forEach(input => {
                const max = parseInt(input.getAttribute('max'));
                const value = parseInt(input.value);
                
                if (value < 1 || value > max) {
                    alert(`La cantidad para este producto debe estar entre 1 y ${max}`);
                    input.focus();
                    allValid = false;
                    e.preventDefault();
                }
            });
            
            return allValid;
        });
    }
});