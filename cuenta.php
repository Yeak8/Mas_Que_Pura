<?php
session_start();
require __DIR__ . '/includes/conexion.php';

// Verificar si ya está logueado
if (isset($_SESSION['usuario'])) {
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            // Regenerar ID de sesión por seguridad
            session_regenerate_id(true);
            
            // Almacenar datos de usuario en sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['loggedin'] = true;
            $_SESSION['admin'] = ($usuario['rol'] === 'admin');
            
            // Redirigir al panel de administración
            header('Location: admin.php');
            exit();
        } else {
            $error = "Credenciales incorrectas";
        }
    } catch (PDOException $e) {
        $error = "Error en el sistema. Por favor intente más tarde.";
    }
}
?>

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
                <li><a href="productos.php">Productos y servicios</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="cuenta.php" id="buyBtn"><img src="svg/iconocuenta.svg" alt="Icono de cuenta" class="iconocuenta"></a></li>
                <li><a href="compra.php" id="loginBtn"><img src="svg/iconocompra.svg" alt="Icono de compra" class="iconocompra"></a></li>
            </ul>
            </nav>
    </header>

        <!-- Modal Login -->
        <div id="loginModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Iniciar Sesión</h2>

                <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Usuario o Email:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
    </div>

    <?php include('footer.php');?>

    <script src="javascript/app.js"></script>
    <script src="javascript/fondonav.js"></script>
</body>
</html>