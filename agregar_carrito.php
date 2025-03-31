<?php
session_start();
require __DIR__ . '/conexion.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Error desconocido'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);
    
    if ($producto_id && $cantidad) {
        // Verificar que el producto existe y tiene stock
        $stmt = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch();
        
        if ($producto && $producto['stock'] >= $cantidad) {
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
            
            $response = [
                'success' => true,
                'message' => 'Producto agregado al carrito'
            ];
        } else {
            $response['message'] = 'No hay suficiente stock disponible';
        }
    } else {
        $response['message'] = 'Datos inválidos';
    }
}

echo json_encode($response);
?>