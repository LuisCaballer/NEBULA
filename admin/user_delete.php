<?php

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$current = currentUser();

if ($id > 0 && $current['id'] !== $id) {
    $stmt = db()->prepare('DELETE FROM usuarios WHERE id = :id');
    $stmt->execute(['id' => $id]);
    setFlash('flash_success', 'Usuario eliminado.');
} else {
    setFlash('flash_error', 'No puedes eliminar tu propio usuario administrador en esta accion.');
}

redirect('users.php');
