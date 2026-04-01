<?php

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM productos WHERE id = :id');
    $stmt->execute(['id' => $id]);
    setFlash('flash_success', 'Producto eliminado.');
}

redirect('products.php');
