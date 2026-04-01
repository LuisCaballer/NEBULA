<?php

require_once __DIR__ . '/includes/auth.php';
requireLogin();

$itemId = (int)($_GET['id'] ?? 0);
$user = currentUser();

$stmt = db()->prepare('DELETE FROM carrito WHERE id = :id AND id_usuario = :id_usuario AND comprado = 0');
$stmt->execute([
    'id' => $itemId,
    'id_usuario' => $user['id'],
]);

setFlash('flash_success', 'Producto eliminado del carrito.');
redirect('cart.php');
