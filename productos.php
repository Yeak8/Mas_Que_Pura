<?php
session_start(); // Añade esto al inicio
require __DIR__ . '/includes/conexion.php';

// Procesar añadir al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_carrito'])) {
    $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);
    
    if ($producto_id && $cantidad) {
        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        // Si el producto ya está en el carrito, sumar la cantidad
        if (isset($_SESSION['carrito'][$producto_id])) {
            $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
        } else {
            // Agregar nuevo producto al carrito
            $_SESSION['carrito'][$producto_id] = [
                'id' => $producto_id,
                'cantidad' => $cantidad
            ];
        }
        
        $_SESSION['mensaje'] = "Producto agregado al carrito";
        header('Location: productos.php');
        exit;
    }
}

// Obtener productos con sus promociones activas
$query = "
    SELECT 
        p.id,
        p.nombre,
        p.descripcion,
        p.precio,
        p.imagen,
        prom.descuento,
        prom.fecha_inicio,
        prom.fecha_fin,
        (p.precio * (1 - IFNULL(prom.descuento, 0) / 100)) AS precio_promocion,
        p.stock
    FROM 
        productos p
    LEFT JOIN 
        promociones prom ON p.id = prom.producto_id 
        AND prom.activa = 1 
        AND CURDATE() BETWEEN prom.fecha_inicio AND prom.fecha_fin
    WHERE 
        p.stock > 0
    ORDER BY 
        p.nombre ASC
";

$productos = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Más Que Pura</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/productos.css">
</head>
<body>

    <?php include('menubanner.php'); ?>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="mensaje-carrito">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
            <?php unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <section id="productos" class="productos-section">
        <div class="container">
            <h1 class="section-title">Nuestros Productos</h1>
            <p class="section-subtitle">Calidad y pureza en cada gota</p>
            
            <div class="productos-grid">
                <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <div class="producto-imagen">
                        <?php if ($producto['imagen']): ?>
                            <img src="uploads/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <?php else: ?>
                            <img src="img/producto-default.jpg" alt="Producto sin imagen">
                        <?php endif; ?>
                        
                        <?php if ($producto['descuento']): ?>
                            <span class="descuento-badge">-<?= $producto['descuento'] ?>%</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="producto-info">
                        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                        <p class="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></p>
                        
                        <div class="precios">
                            <?php if ($producto['descuento']): ?>
                                <span class="precio-original">$<?= number_format($producto['precio'], 2) ?></span>
                                <span class="precio-promo">$<?= number_format($producto['precio_promocion'], 2) ?></span>
                                <span class="promo-msg">Oferta válida hasta <?= date('d/m/Y', strtotime($producto['fecha_fin'])) ?></span>
                            <?php else: ?>
                                <span class="precio-normal">$<?= number_format($producto['precio'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="producto-acciones">
                            <button class="btn-detalles">Ver Detalles</button>
                            <form method="POST" action="productos.php" class="form-carrito">
                                <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>" class="cantidad-input">
                                <button type="submit" name="agregar_carrito" class="btn-comprar">Añadir al Carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($productos)): ?>
                    <div class="no-productos">
                        <p>Actualmente no hay productos disponibles. Vuelve pronto.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <script src="javascript/app.js"></script>
    <script src="javascript/productos.js"></script>
</body>
</html>