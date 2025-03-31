<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Más Que Pura - Agua Purificada</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=MXN"></script>
</head>
<body>
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
                <li><a href="productos.php">Productos y servicios</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="cuenta.php" id="buyBtn"><img src="svg/iconocuenta.svg" alt="Icono de cuenta" class="iconocuenta"></a></li>
                <li><a href="compra.php" id="loginBtn"><img src="svg/iconocompra.svg" alt="Icono de compra" class="iconocompra"></a></li>
            </ul>
            </nav>
    </header>

    <section id="tienda" class="tienda">
        <h2>Nuestra Tienda Física</h2>
        <div class="tienda-info">
            <p>📍 Dirección: Fusce at nisi eget dolor rhoncus facilisis</p>
            <p>📞 Tel: (999) 999-9999</p>
            <p>🕒 Horario: Lunes a Viernes 9am - 6pm</p>
        </div>
    </section>

    <?php include('footer.php');?>
    <script src="javascript/app.js"></script>
    <script src="javascript/fondonav.js"></script>
</body>
</html>