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
                <img class="logocostado" src="assets/img/MQPblanco.png" alt="Logo de la marca">
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="productos.php" class="active">Productos y servicios</a></li>
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

<script src="assets/js/iconocarrito.js"></script>