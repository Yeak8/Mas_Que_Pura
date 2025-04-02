fetch('includes/obtener_contador_carrito.php')
  .then(response => response.json())
  .then(data => {
    // Manejar la respuesta
  });

// Actualizar contador del carrito dinámicamente
document.addEventListener('DOMContentLoaded', function() {
    function updateCartCounter() {
        fetch('obtener_contador_carrito.php')
            .then(response => response.json())
            .then(data => {
                const counter = document.getElementById('cart-counter');
                if (data.totalItems > 0) {
                    counter.textContent = data.totalItems > 9 ? '9+' : data.totalItems;
                    counter.style.display = 'flex';
                } else {
                    counter.style.display = 'none';
                }
            });
    }
    
    // Actualizar cada 5 segundos (opcional)
    setInterval(updateCartCounter, 5000);
});