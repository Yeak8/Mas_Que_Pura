<?php
session_start();
require __DIR__ . '/includes/conexion.php';

// Verificar autenticación y permisos
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    $_SESSION['error'] = "Acceso no autorizado";
    header('Location: login.php');
    exit;
}

// Configurar logging detallado
ini_set('display_errors', 1);
error_reporting(E_ALL);
logOperation("Inicio de eliminación de producto");

// Solo procesar si es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logOperation("Intento de acceso con método no POST");
    $_SESSION['error'] = "Método no permitido";
    header('Location: admin.php');
    exit;
}

// Validar ID
$id_producto = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id_producto === false || $id_producto <= 0) {
    logOperation("ID de producto inválido recibido: " . print_r($_POST, true));
    $_SESSION['error'] = "ID de producto inválido";
    header('Location: admin.php');
    exit;
}

try {
    logOperation("Intentando eliminar producto ID: $id_producto");
    
    // Iniciar transacción
    $pdo->beginTransaction();
    
    // 1. Verificar si el producto existe
    $consulta = $pdo->prepare("SELECT id, nombre FROM productos WHERE id = ?");
    $consulta->execute([$id_producto]);
    $producto = $consulta->fetch();
    
    if (!$producto) {
        logOperation("Producto no encontrado ID: $id_producto");
        $_SESSION['error'] = "El producto no existe o ya fue eliminado";
        $pdo->rollBack();
        header('Location: admin.php');
        exit;
    }
    
    // 2. Eliminar posibles relaciones primero (promociones, etc.)
    try {
        $pdo->prepare("DELETE FROM promociones WHERE producto_id = ?")->execute([$id_producto]);
        logOperation("Eliminadas promociones relacionadas al producto ID: $id_producto");
    } catch (Exception $e) {
        logOperation("No se pudieron eliminar promociones: " . $e->getMessage());
    }
    
    // 3. Eliminar el producto
    $consulta = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $consulta->execute([$id_producto]);
    
    if ($consulta->rowCount() > 0) {
        $pdo->commit();
        logOperation("Producto eliminado exitosamente: " . $producto['nombre']);
        $_SESSION['mensaje'] = "Producto '" . htmlspecialchars($producto['nombre']) . "' eliminado correctamente";
    } else {
        $pdo->rollBack();
        logOperation("No se afectaron filas al eliminar producto ID: $id_producto");
        $_SESSION['error'] = "No se pudo eliminar el producto (ningún cambio realizado)";
    }
    
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $errorMsg = "Error al eliminar producto: " . $e->getMessage();
    logOperation($errorMsg);
    error_log($errorMsg);
    
    $_SESSION['error'] = "Error al eliminar el producto. Código: " . $e->getCode();
    
    // Mensaje más específico según el código de error
    if ($e->getCode() == '23000') {
        $_SESSION['error'] .= " (Restricción de clave foránea)";
    }
}

header('Location: admin.php');
exit;

// Función auxiliar para logging
function logOperation($message) {
    $logFile = __DIR__ . '/logs/delete_products.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}
?>