<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificación de sesión mejorada
$current_page = basename($_SERVER['PHP_SELF']);

// Si no está logueado, redirigir a login con URL de retorno
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

// Si está logueado pero no es admin
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    // Usar die() en lugar de redirección para evitar bucles
    die("<div class='alert error'>Acceso denegado. No tienes permisos de administrador. <a href='index.php'>Volver al inicio</a></div>");
}

// Mostrar mensajes de sesión
if (isset($_SESSION['mensaje'])) {
    echo '<div class="alert success">'.htmlspecialchars($_SESSION['mensaje']).'</div>';
    unset($_SESSION['mensaje']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert error">'.htmlspecialchars($_SESSION['error']).'</div>';
    unset($_SESSION['error']);
}

// Conexión a BD
require __DIR__ . '/includes/conexion.php';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require __DIR__ . '/includes/procesar_formularios.php';
    exit;
}

// Obtener datos
try {
    $productos = $pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    
    $promociones = $pdo->query("
        SELECT p.*, pr.nombre as producto_nombre, pr.precio as precio_original
        FROM promociones p 
        JOIN productos pr ON p.producto_id = pr.id
        WHERE p.activa = 1 AND CURDATE() BETWEEN p.fecha_inicio AND p.fecha_fin
    ")->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Error BD: " . $e->getMessage());
    die("<div class='alert error'>Error al cargar datos. Consulte el administrador.</div>");
}

// Añadir al inicio de admin.php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include('menuburger.php');?>
    <header class="admin-header">
        <h1>Panel de Administración</h1>
        <div class="user-info">
            Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre_completo']) ?>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </header>

    <main class="admin-container">
        <!-- Sección de Productos -->
        <section class="admin-section">
            <h2>Gestión de Productos</h2>
            
            <!-- Formulario para agregar productos -->
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                <h3>Agregar Nuevo Producto</h3>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <textarea name="descripcion" placeholder="Descripción" required></textarea>
                <input type="number" step="0.01" name="precio" placeholder="Precio" required>
                <input type="number" name="stock" placeholder="Stock" required>
                <input type="file" name="imagen" accept="image/*">
                <button type="submit" name="agregar_producto">Agregar Producto</button>
            </form>
            
            <!-- Lista de productos -->
            <div class="admin-list">
                <h3>Productos Existentes</h3>
                <table>
                    <!-- Cabecera de la tabla -->
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <!-- Cuerpo de la tabla -->
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id'] ?></td>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td>$<?= number_format($producto['precio'], 2) ?></td>
                            <td><?= $producto['stock'] ?></td>
                            <td class="actions">
    <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn-edit">Editar</a>
    <form method="POST" action="eliminar_producto.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
        <button type="submit" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
    </form>
</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        <!-- Sección de Promociones -->
        <section class="admin-section">
            <h2>Gestión de Promociones</h2>
            
            <!-- Formulario para agregar promociones -->
            <form method="POST" class="admin-form">
                <h3>Crear Nueva Promoción</h3>
                <select name="producto_id" required>
                    <option value="">Seleccionar Producto</option>
                    <?php foreach ($productos as $producto): ?>
                    <option value="<?= $producto['id'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" step="0.01" name="descuento" placeholder="% Descuento" min="1" max="100" required>
                <div class="form-row">
                    <label>Fecha Inicio: <input type="date" name="fecha_inicio" required></label>
                    <label>Fecha Fin: <input type="date" name="fecha_fin" required></label>
                </div>
                <button type="submit" name="agregar_promocion">Crear Promoción</button>
            </form>
            
            <!-- Lista de promociones -->
            <div class="admin-list">
                <h3>Promociones Activas</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Descuento</th>
                            <th>Precio Promo</th>
                            <th>Válido hasta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones as $promo): 
                            $precio_promo = $promo['precio_original'] * (1 - ($promo['descuento'] / 100));
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($promo['producto_nombre']) ?></td>
                            <td><?= $promo['descuento'] ?>%</td>
                            <td>$<?= number_format($precio_promo, 2) ?></td>
                            <td><?= date('d/m/Y', strtotime($promo['fecha_fin'])) ?></td>
                            <td class="actions">
    <form method="POST" action="eliminar_promocion.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $promo['id'] ?>">
        <button type="submit" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta promoción?')">Eliminar</button>
    </form>
</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>


<!-- En el formulario de productos, antes del cierre </form> -->
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    console.log("Datos del formulario:", {
        nombre: this.nombre.value,
        descripcion: this.descripcion.value,
        precio: this.precio.value,
        stock: this.stock.value,
        imagen: this.imagen.files[0] ? this.imagen.files[0].name : 'Sin imagen'
    });
});
</script>



    <footer class="admin-footer">
        Sistema de Administración - © <?= date('Y') ?>
    </footer>
</body>
</html>