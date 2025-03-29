<?php
function loginUsuario($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE (username = ? OR email = ?) AND activo = TRUE LIMIT 1");
    $stmt->execute([$username, $username]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
        
        // Almacenar datos de usuario en sesión (excepto contraseña)
        unset($usuario['password_hash']);
        $_SESSION['usuario'] = $usuario;
        $_SESSION['admin'] = ($usuario['rol'] === 'admin');
        $_SESSION['loggedin'] = true;
        $_SESSION['LAST_ACTIVITY'] = time();
        
        return $usuario;
    }
    return false;
}

function verificarAdmin() {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header('Location: login.php');
        exit();
    }
    
    // Verificar inactividad (30 minutos)
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        session_unset();
        session_destroy();
        header('Location: login.php?timeout=1');
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>