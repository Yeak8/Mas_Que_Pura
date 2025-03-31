<?php
session_start();
require __DIR__ . '/includes/conexion.php';

// Verificar autenticación y permisos
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    $_SESSION['error'] = "Acceso no autorizado";
    header('Location: login.php');
    exit;
}

// Configurar logging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Solo procesar si es POST (más seguro que GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Método no permitido";
    header('Location: admin.php');
    exit;
}

// Validar ID
$id_promocion = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id_promocion === false || $id_promocion <= 0) {
    $_SESSION['error'] = "ID de promoción inválido";
    header('Location: admin.php');
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();
    
    // 1. Verificar si la promoción existe
    $consulta = $pdo->prepare("SELECT id, producto_id, descuento FROM promociones WHERE id = ?");
    $consulta->execute([$id_promocion]);
    $promocion = $consulta->fetch();
    
    if (!$promocion) {
        $_SESSION['error'] = "La promoción no existe o ya fue eliminada";
        $pdo->rollBack();
        header('Location: admin.php');
        exit;
    }
    
    // 2. Eliminar la promoción
    $consulta = $pdo->prepare("DELETE FROM promociones WHERE id = ?");
    $consulta->execute([$id_promocion]);
    
    if ($consulta->rowCount() > 0) {
        $pdo->commit();
        $_SESSION['mensaje'] = "Promoción eliminada correctamente";
        
        // Opcional: Registrar acción
        error_log("Promoción eliminada ID: $id_promocion, Producto ID: {$promocion['producto_id']}, Descuento: {$promocion['descuento']}%");
    } else {
        $pdo->rollBack();
        $_SESSION['error'] = "No se pudo eliminar la promoción (ningún cambio realizado)";
    }
    
} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    $errorMsg = "Error al eliminar promoción: " . $e->getMessage();
    error_log($errorMsg);
    
    // Mensaje de error específico para restricciones de clave foránea
    if ($e->getCode() == '23000') {
        $_SESSION['error'] = "No se puede eliminar la promoción porque tiene registros relacionados";
    } else {
        $_SESSION['error'] = "Error al eliminar la promoción. Código: " . $e->getCode();
    }
}

header('Location: admin.php');
exit;
?>