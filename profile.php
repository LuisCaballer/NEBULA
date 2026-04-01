<?php

require_once __DIR__ . '/includes/layout.php';
requireLogin();

$user = currentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['nueva_contrasena'] ?? '';
    $confirmPassword = $_POST['confirmar_contrasena'] ?? '';

    if ($newPassword !== $confirmPassword) {
        setFlash('flash_error', 'Las contraseñas no coinciden.');
        redirect('profile.php');
    }

    if (strlen($newPassword) < 6) {
        setFlash('flash_error', 'La nueva contraseña debe tener al menos 6 caracteres.');
        redirect('profile.php');
    }

    $stmt = db()->prepare('UPDATE usuarios SET contrasena = :contrasena WHERE id = :id');
    $stmt->execute([
        'contrasena' => password_hash($newPassword, PASSWORD_DEFAULT),
        'id' => $user['id'],
    ]);

    setFlash('flash_success', 'Contraseña actualizada con éxito.');
    redirect('profile.php');
}

renderHeader('Mi perfil');
?>

<section class="panel narrow">
    <h2>Mi perfil</h2>
    <p><strong>Nombre:</strong> <?= e($user['nombre'] . ' ' . $user['apellido']) ?></p>
    <p><strong>Correo:</strong> <?= e($user['correo']) ?></p>
    <p><strong>Rol:</strong> <?= (int)$user['es_admin'] === 1 ? 'Administrador' : 'Usuario' ?></p>

    <h3>Cambiar contraseña</h3>
    <form method="post" class="form-grid">
        <label>Nueva contraseña</label>
        <input type="password" name="nueva_contrasena" required>

        <label>Confirmar nueva contraseña</label>
        <input type="password" name="confirmar_contrasena" required>

        <button type="submit">Actualizar contraseña</button>
    </form>
</section>

<?php renderFooter(); ?>
