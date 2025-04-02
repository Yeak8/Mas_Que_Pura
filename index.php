<?php
// Solo iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Más Que Pura - Agua Purificada</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/movil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;900&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/gloss-and-bloom" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
<?php include('menuburger.php');?>

    <section id="inicio" class="banner">
        <div class="banner-content">
            <img class="masquepura" src="img/Nombre/Masquepuranombre.png" alt="Logo" class="masquepura">
        </div>
    </section>

    <section id="quiensomos" class="bannerquiensomos">
        <div class="banner-content">
           <h1 class="tituloblanco">¿Quienes Somos?</h1>
           <br>
           <p class="quienessomosdescrip">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo possimus eaque, officiis quisquam consequuntur quam quis ratione nam quasi at ea accusantium minima est molestiae similique earum, placeat dicta nemo!</p>
        </div>
    </section>





<div>
    <br><br><br>
    <h2 class="titulo">Nuestro Trabajo</h2>
    <br><br><br>

    <div class="grid-contenedor">
        <div class="grid-item one">
            <img src="img/Foto 1.jpg" alt="Imagen 1">
            <div class="overlay"><p>Descripción de la imagen 1</p></div>
        </div>
        <div class="grid-item two">
            <img src="img/Foto 2.jpg" alt="Imagen 2">
            <div class="overlay"><p>Descripción de la imagen 2</p></div>
        </div>
        <div class="grid-item three">
            <img src="img/Foto 3.jpg" alt="Imagen 3">
            <div class="overlay"><p>Descripción de la imagen 3</p></div>
        </div>
        <div class="grid-item four">
            <img src="img/Foto 4.jpg" alt="Imagen 4">
            <div class="overlay"><p>Descripción de la imagen 4</p></div>
        </div>
        <div class="grid-item five">
            <img src="img/Foto 5.jpg" alt="Imagen 5">
            <div class="overlay"><p>Descripción de la imagen 5</p></div>
        </div>
    </div>
</div>



<div>
    <br><br><br>
    <h2 class="titulo">Testimonios</h2>

    <div class="testimonios-contenedor">
        <div class="slides-contenedor">
            <!-- Slide 1 -->
            <div class="testimonio-slide active">
                <div class="testimonio-imagen-container">
                    <img src="img/Persona1.jpg" alt="Karely" class="testimonio-imagen">
                </div>
                <h3 class="estilonombre">ANDRES</h3>
                <br><br>
                <p class="estilotestimonio">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam, mollitia consectetur dignissimos a nisi repudiandae reiciendis itaque dicta animi dolorum, odio nihil ducimus praesentium repellat, temporibus quia ullam at rerum.
                </p>
            </div>

            <!-- Slide 2 -->
            <div class="testimonio-slide">
                <div class="testimonio-imagen-container">
                    <img src="img/Persona2.jpg" alt="María" class="testimonio-imagen">
                </div>
                <h3 class="estilonombre">JOSÉ</h3>
                <br><br>
                <p class="estilotestimonio">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse sequi animi inventore. Ipsam dolorum nostrum at repudiandae natus, ullam quo architecto magnam possimus eligendi, soluta ipsum fugit aut distinctio aperiam!
                </p>
            </div>

            <!-- Slide 3 -->
            <div class="testimonio-slide">
                <div class="testimonio-imagen-container">
                    <img src="img/Persona3.jpg" alt="Pedro" class="testimonio-imagen">
                </div>
                <br><br>
                <h3 class="estilonombre">KARELY</h3>
                <br><br>
                <p class="estilotestimonio">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Consequuntur animi vitae minima? Voluptatum repellat tempora sint non odio provident. Enim at debitis, autem et atque itaque porro modi ut voluptate!
                </p>
            </div>
        </div>

        <button class="nav-btn prev-btn">❮</button>
        <button class="nav-btn next-btn">❯</button>

        <div class="indicadores">
            <div class="indicador active"></div>
            <div class="indicador"></div>
            <div class="indicador"></div>
        </div>
    </div>

</div>


<?php include('footer.php');?>

<script src="javascript/hamburguesa.js"></script>
<script src="javascript/botones.js"></script>
<script src="javascript/iconocarrito.js"></script>
<script src="javascript/descripfotos.js"></script>
    <script src="javascript/app.js"></script>
    <script src="javascript/fondonav.js"></script>
</body>
</html>