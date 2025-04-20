<?php
// Verificar si la conexión ya existe
if (!isset($pdo)) {
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=sistema_administracion;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        error_log("Error de conexión: " . $e->getMessage());
        die("Error al conectar con la base de datos. Por favor intente más tarde.");
    }
}
?>