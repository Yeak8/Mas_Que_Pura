<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Más Que Pura - Agua Purificada</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/contacto.css">
    <link rel="stylesheet" href="assets/css/movil.css">
    <script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=MXN"></script>
</head>
<body>
<?php include('menuburger.php');?>
    

    <section class="bannercontacto">
        <div class="banner-overlay"></div>
        <div class="contact-container">
            <div class="contact-info">
            <br><br><br><br>
                <div class="info-item">
                    <h3>Location</h3>
                    <p>Av. Pureza 456, Col. Agua Cristalina<br>Guadalajara, Jalisco</p>
                </div>
                <div class="info-item">
                    <h3>Email</h3>
                    <p>info@masquepura.com</p>
                </div>
                <div class="info-item">
                    <h3>Phone</h3>
                    <p>(33) 1234-5678</p>
                </div>
            </div>
            
            <div class="contact-form">
                <h2>Envienos un mensaje</h2>
                <form>
                    <input type="text" placeholder="Nombre" required>
                    <input type="email" placeholder="Correo" required>
                    <textarea placeholder="Mensaje" required></textarea>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </section>

    <?php include('footer.php');?>
    
    <script src="assets/js/app.js"></script>
    <script src="assets/js/hamburguesa.js"></script>
    <script src="assets/js/fondonav.js"></script>
</body>
</html>