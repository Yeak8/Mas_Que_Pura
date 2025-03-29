<?php
session_start();
header('Content-Type: application/json');

$totalItems = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        if (is_array($item) && isset($item['cantidad'])) {
            $totalItems += (int)$item['cantidad'];
        }
    }
}

echo json_encode(['totalItems' => $totalItems]);
?>