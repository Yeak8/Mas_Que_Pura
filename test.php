<?php
require __DIR__ . '/includes/conexion.php';

echo "<h2>Prueba de conexión a la base de datos</h2>";

try {
    // Prueba consulta simple
    $stmt = $pdo->query("SELECT 1");
    echo "<p>✅ Conexión PDO funcionando correctamente</p>";
    
    // Verificar tablas necesarias
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $requiredTables = ['usuarios', 'productos', 'promociones'];
    
    foreach ($requiredTables as $table) {
        if (!in_array($table, $tables)) {
            echo "<p>❌ Falta la tabla: $table</p>";
        } else {
            echo "<p>✅ Tabla '$table' existe</p>";
        }
    }
    
    // Verificar usuario admin
    $admin = $pdo->query("SELECT * FROM usuarios WHERE username = 'admin'")->fetch();
    if ($admin) {
        echo "<p>✅ Usuario admin encontrado</p>";
    } else {
        echo "<p>❌ Usuario admin no encontrado</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error en la base de datos: " . $e->getMessage() . "</p>";
}
?>