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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
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
</body>
</html>