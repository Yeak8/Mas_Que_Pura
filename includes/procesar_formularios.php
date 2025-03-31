<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar conexión a BD
if (!isset($pdo)) {
    $_SESSION['error'] = "Error de conexión a la base de datos";
    header('Location: admin.php');
    exit;
}

// Solo procesar si es método POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: admin.php');
    exit;
}

// Debug: Registrar datos recibidos
error_log("Datos POST recibidos: " . print_r($_POST, true));
error_log("Datos FILES recibidos: " . print_r($_FILES, true));

// Procesar formulario de producto
if (isset($_POST['agregar_producto'])) {
    try {
        // Validar y sanitizar entradas
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
        
        // Procesar imagen
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/productos/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception("No se pudo crear el directorio para imágenes");
                }
            }
            
            $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreImagen = uniqid() . '.' . $extension;
            
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $nombreImagen)) {
                throw new Exception("Error al mover la imagen subida");
            }
            
            $imagen = $nombreImagen;
            error_log("Imagen guardada: " . $nombreImagen);
        }
        
        // Insertar en BD
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen, usuario_creador) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $imagen, $_SESSION['usuario']['id']]);
        
        // Verificar inserción
        if ($stmt->rowCount() > 0) {
            $lastId = $pdo->lastInsertId();
            $_SESSION['mensaje'] = "Producto agregado correctamente (ID: $lastId)";
            error_log("Producto insertado correctamente. ID: $lastId");
        } else {
            throw new Exception("No se insertó ningún registro");
        }
        
    } catch (Exception $e) {
        error_log("Error al agregar producto: " . $e->getMessage());
        $_SESSION['error'] = "Error al agregar producto: " . $e->getMessage();
    }
}

// Procesar formulario de promoción
if (isset($_POST['agregar_promocion'])) {
    try {
        $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_SANITIZE_NUMBER_INT);
        $descuento = filter_input(INPUT_POST, 'descuento', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $fecha_inicio = filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_STRING);
        $fecha_fin = filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_STRING);
        
        $stmt = $pdo->prepare("INSERT INTO promociones (producto_id, descuento, fecha_inicio, fecha_fin, usuario_creador) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$producto_id, $descuento, $fecha_inicio, $fecha_fin, $_SESSION['usuario']['id']]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['mensaje'] = "Promoción creada correctamente";
            error_log("Promoción creada para producto ID: $producto_id");
        } else {
            throw new Exception("No se pudo crear la promoción");
        }
        
    } catch (Exception $e) {
        error_log("Error al crear promoción: " . $e->getMessage());
        $_SESSION['error'] = "Error al crear promoción: " . $e->getMessage();
    }
}

// Debug final
error_log("Redirigiendo a admin.php");
header('Location: admin.php');
exit;
?>