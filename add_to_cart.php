<?php

require_once __DIR__ . '/includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$productId = (int)($_POST['product_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);
$quantity = max(1, $quantity);

$stmt = db()->prepare('SELECT id FROM productos WHERE id = :id AND activo = 1 LIMIT 1');
$stmt->execute(['id' => $productId]);

if (!$stmt->fetch()) {
    setFlash('flash_error', 'El producto no existe.');
    redirect('index.php');
}

$user = currentUser();

$existing = db()->prepare('SELECT id, cantidad_producto FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto AND comprado = 0 LIMIT 1');
$existing->execute([
    'id_usuario' => $user['id'],
    'id_producto' => $productId,
]);
$row = $existing->fetch();

if ($row) {
    $update = db()->prepare('UPDATE carrito SET cantidad_producto = :cantidad WHERE id = :id');
    $update->execute([
        'cantidad' => (int)$row['cantidad_producto'] + $quantity,
        'id' => $row['id'],
    ]);
} else {
    $insert = db()->prepare('INSERT INTO carrito (id_usuario, id_producto, cantidad_producto, comprado) VALUES (:id_usuario, :id_producto, :cantidad, 0)');
    $insert->execute([
        'id_usuario' => $user['id'],
        'id_producto' => $productId,
        'cantidad' => $quantity,
    ]);
}

setFlash('flash_success', 'Producto agregado al carrito.');
redirect('cart.php');
