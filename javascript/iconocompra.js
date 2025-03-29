// Función para actualizar el contador del carrito
function updateCartCounter() {
    // Obtener los productos del carrito (pueden estar en localStorage, sessionStorage o cookies)
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    
    // Obtener el elemento del contador
    const cartCounter = document.getElementById('cart-counter');
    
    // Calcular el total de items
    let totalItems = 0;
    cartItems.forEach(item => {
        totalItems += item.quantity || 1;
    });
    
    // Actualizar el contador
    if (totalItems > 0) {
        cartCounter.textContent = totalItems;
        cartCounter.style.display = 'flex';
        
        // Opcional: Mostrar solo punto rojo si hay más de 9 items
        if (totalItems > 9) {
            cartCounter.textContent = '9+';
            // O alternativamente:
            // cartCounter.classList.add('dot-only');
        }
    } else {
        cartCounter.style.display = 'none';
    }
}

// Llamar a la función cuando la página cargue
document.addEventListener('DOMContentLoaded', updateCartCounter);

// También cuando se agregue un producto (esto debe llamarse desde tu código de agregar al carrito)
function addToCart(product) {
    // Tu lógica actual para agregar al carrito...
    
    // Después de agregar, actualizar el contador
    updateCartCounter();
    
    // Opcional: Mostrar notificación flotante
    showCartNotification('Producto agregado al carrito');
}

// Función para mostrar notificación flotante
function showCartNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animación y remoción después de 3 segundos
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// JavaScript modificado para usar PHP
function updateCartCounter() {
    fetch('obtener_contador_carrito.php')
        .then(response => response.json())
        .then(data => {
            const cartCounter = document.getElementById('cart-counter');
            if (data.totalItems > 0) {
                cartCounter.textContent = data.totalItems;
                cartCounter.style.display = 'flex';
            } else {
                cartCounter.style.display = 'none';
            }
        });
}


// Escuchar cambios en el localStorage (entre pestañas)
window.addEventListener('storage', function(event) {
    if (event.key === 'cartItems') {
        updateCartCounter();
    }
});

// O usando EventSource para actualizaciones del servidor
const eventSource = new EventSource('carrito_updates.php');
eventSource.onmessage = function(e) {
    updateCartCounter();
};