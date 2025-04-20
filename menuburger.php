<?php
// Solo iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
        <nav>
            <div class="logo-contenedor">
                <a href="index.php">
                    <img class="logocostado" src="assets/img/MQPblanco.png" alt="Logo de la marca">
                </a>
            </div>
    
            <!-- Menú Hamburguesa -->
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
    
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li class="centrado"><a href="productos.php">Productos y servicios</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="cuenta.php" id="buyBtn"><img src="svg/iconocuenta.svg" alt="Icono de cuenta" class="iconocuenta"></a></li>
                <li><a href="compra.php" id="loginBtn"><img src="svg/iconocompra.svg" alt="Icono de compra" class="iconocompra"></a></li>
            </ul>
        </nav>
    </header>

    <script src="javascript/iconocarrito.js"></script>