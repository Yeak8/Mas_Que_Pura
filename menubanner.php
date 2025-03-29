<?php
// Solo iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav>
        <div class="logo-container">
            <a href="index.php">
                <img class="logocostado" src="svg/logocostado.svg" alt="Logo de la marca">
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="tienda.php">Tienda</a></li>
            <li><a href="productos.php" class="active">Productos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="cuenta.php" id="buyBtn"><img src="svg/iconocuenta.svg" alt="Icono de cuenta" class="iconocuenta"></a></li>
            <li class="cart-icon-container">
    <a href="compra.php" id="loginBtn" class="keychainify-checked">
        <img src="svg/iconocompra.svg" alt="Icono de compra" class="iconocompra">
        <span id="cart-counter" class="cart-counter" style="display: none;">0</span>
    </a>
</li>
        </ul>
    </nav>
</header>

<script>
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
</script>