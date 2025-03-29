document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad para los botones
    const botonesComprar = document.querySelectorAll('.btn-comprar');
    
    botonesComprar.forEach(boton => {
        boton.addEventListener('click', function() {
            const productoCard = this.closest('.producto-card');
            const nombreProducto = productoCard.querySelector('h3').textContent;
            
            // Aquí puedes agregar la lógica para añadir al carrito
            console.log(`Producto añadido al carrito: ${nombreProducto}`);
            
            // Mostrar notificación
            alert(`¡${nombreProducto} añadido al carrito!`);
        });
    });
    
    // Funcionalidad para los botones de detalles
    const botonesDetalles = document.querySelectorAll('.btn-detalles');
    
    botonesDetalles.forEach(boton => {
        boton.addEventListener('click', function() {
            const productoCard = this.closest('.producto-card');
            const nombreProducto = productoCard.querySelector('h3').textContent;
            
            // Aquí puedes redirigir a una página de detalles o mostrar un modal
            console.log(`Ver detalles de: ${nombreProducto}`);
        });
    });
});