<?php
session_start();
require __DIR__ . '/includes/conexion.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Procesar eliminación de productos del carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_item'])) {
    $id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
    if ($id !== false && isset($_SESSION['carrito'][$id])) {
        unset($_SESSION['carrito'][$id]);
        $_SESSION['mensaje'] = "Producto eliminado del carrito";
    }
}

// Procesar actualización de cantidades
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_cantidad'])) {
    if (isset($_POST['cantidad']) && is_array($_POST['cantidad'])) {
        foreach ($_POST['cantidad'] as $id => $cantidad) {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 99]
            ]);
            
            if ($id !== false && $cantidad !== false && isset($_SESSION['carrito'][$id])) {
                $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
            }
        }
        $_SESSION['mensaje'] = "Carrito actualizado";
    }
}

// Obtener información completa de los productos en el carrito
$carrito_detallado = [];
$total = 0;

if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $id => $item) {
        // Verificar que $item es un array válido
        if (!is_array($item)) {
            continue; // Este continue ahora está dentro del foreach
        }
        
        $stmt = $pdo->prepare("SELECT p.*, 
                              (SELECT descuento FROM promociones 
                               WHERE producto_id = p.id AND activa = 1 
                               AND CURDATE() BETWEEN fecha_inicio AND fecha_fin) as descuento
                              FROM productos p WHERE p.id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch();
        
        if ($producto && is_array($producto)) {
            $precio = isset($producto['descuento']) && $producto['descuento'] ? 
                     $producto['precio'] * (1 - $producto['descuento'] / 100) : 
                     $producto['precio'];
            
            $cantidad = isset($item['cantidad']) ? max(1, (int)$item['cantidad']) : 1;
            $subtotal = $precio * $cantidad;
            $total += $subtotal;
            
            $carrito_detallado[] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'] ?? 'Producto no disponible',
                'precio' => $producto['precio'] ?? 0,
                'precio_final' => $precio,
                'descuento' => $producto['descuento'] ?? null,
                'cantidad' => $cantidad,
                'imagen' => $producto['imagen'] ?? 'producto-default.jpg',
                'subtotal' => $subtotal,
                'stock' => $producto['stock'] ?? 0
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Más Que Pura</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/compra.css">
</head>
<body>
    <?php include('menubanner.php'); ?>

    <main class="compra-container">
        <h1>Tu Carrito de Compras</h1>
        
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        
        <?php if (empty($carrito_detallado)): ?>
            <div class="carrito-vacio">
                <p>Tu carrito está vacío</p>
                <a href="productos.php" class="btn">Ver Productos</a>
            </div>
        <?php else: ?>
            <form method="post" action="compra.php" class="form-carrito">
                <div class="items-carrito">
                    <?php foreach ($carrito_detallado as $item): ?>
                    <div class="item-carrito">
                        <div class="item-imagen">
                            <img src="uploads/productos/<?= htmlspecialchars($item['imagen']) ?>" 
                                 alt="<?= htmlspecialchars($item['nombre']) ?>">
                        </div>
                        
                        <div class="item-info">
                            <h3><?= htmlspecialchars($item['nombre']) ?></h3>
                            
                            <div class="item-precio">
                                <?php if ($item['descuento']): ?>
                                    <span class="precio-original">$<?= number_format($item['precio'], 2) ?></span>
                                    <span class="precio-final">$<?= number_format($item['precio_final'], 2) ?></span>
                                    <span class="descuento">-<?= $item['descuento'] ?>%</span>
                                <?php else: ?>
                                    <span class="precio-final">$<?= number_format($item['precio_final'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="item-cantidad">
                                <label>Cantidad:</label>
                                <input type="number" name="cantidad[<?= $item['id'] ?>]" 
                                       value="<?= $item['cantidad'] ?>" min="1" max="<?= $item['stock'] ?>">
                            </div>
                            
                            <div class="item-subtotal">
                                Subtotal: $<?= number_format($item['subtotal'], 2) ?>
                            </div>
                            
                            <div class="item-acciones">
                                <button type="submit" name="actualizar_cantidad" class="btn-actualizar">
                                    Actualizar
                                </button>
                                <button type="submit" name="eliminar_item" class="btn-eliminar">
                                    Eliminar
                                </button>
                                <input type="hidden" name="producto_id" value="<?= $item['id'] ?>">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="resumen-compra">
                    <h2>Resumen de Compra</h2>
                    
                    <div class="resumen-detalle">
                        <div class="resumen-fila">
                            <span>Subtotal:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="resumen-fila">
                            <span>Envío:</span>
                            <span>$<?= number_format(0, 2) ?></span>
                        </div>
                        <div class="resumen-fila total">
                            <span>Total:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    
                    <div class="resumen-acciones">
                        <a href="productos.php" class="btn-seguir">Seguir Comprando</a>
                        <a href="checkout.php" class="btn-pagar">Proceder al Pago</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <?php include('footer.php'); ?>

    <script src="javascript/app.js"></script>
    <script src="javascript/compra.js"></script>
</body>
</html>