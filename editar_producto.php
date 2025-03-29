<?php
session_start();

// Verificar autenticación y permisos
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/includes/conexion.php';

// Obtener el ID del producto a editar
$producto_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$producto_id) {
    $_SESSION['error'] = "ID de producto no válido";
    header('Location: admin.php');
    exit;
}

// Obtener datos actuales del producto
try {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        $_SESSION['error'] = "Producto no encontrado";
        header('Location: admin.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error al obtener el producto: " . $e->getMessage();
    header('Location: admin.php');
    exit;
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_producto'])) {
    try {
        // Validar y sanitizar entradas
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
        
        // Procesar imagen (si se subió una nueva)
        $imagen = $producto['imagen']; // Mantener la imagen actual por defecto
        
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/productos/';
            
            // Crear directorio si no existe
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Eliminar imagen anterior si existe
            if (!empty($producto['imagen']) && file_exists($uploadDir . $producto['imagen'])) {
                unlink($uploadDir . $producto['imagen']);
            }
            
            // Subir nueva imagen
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreImagen = uniqid() . '.' . $extension;
            
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $nombreImagen)) {
                $imagen = $nombreImagen;
            } else {
                throw new Exception("Error al mover la nueva imagen");
            }
        }
        
        // Actualizar en BD
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $imagen, $producto_id]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['mensaje'] = "Producto actualizado correctamente";
            header('Location: admin.php');
            exit;
        } else {
            $_SESSION['error'] = "No se realizaron cambios en el producto";
            header('Location: admin.php');
            exit;
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al actualizar producto: " . $e->getMessage();
        header('Location: editar_producto.php?id=' . $producto_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Panel de Administración</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header class="admin-header">
        <h1>Editar Producto</h1>
        <div class="user-info">
            Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre_completo']) ?>
            <a href="../logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <main class="admin-container">
        <section class="admin-section">
            <a href="admin.php" class="btn-back">← Volver al Panel</a>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" id="precio" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($producto['stock']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <?php if (!empty($producto['imagen'])): ?>
                        <p>Imagen actual:</p>
                        <img src="../uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen actual" class="imagen-actual">
                        <p>Cambiar imagen:</p>
                    <?php endif; ?>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
                
                <button type="submit" name="actualizar_producto" class="btn-submit">Actualizar Producto</button>
            </form>
        </section>
    </main>

    <footer class="admin-footer">
        Sistema de Administración - © <?= date('Y') ?>
    </footer>
</body>
</html>