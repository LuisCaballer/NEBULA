<?php

require_once __DIR__ . '/includes/layout.php';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($contrasena !== $confirmar) {
        setFlash('flash_error', 'Las contraseñas no coinciden.');
        redirect('register.php');
    }

    if (strlen($contrasena) < 6) {
        setFlash('flash_error', 'La contraseña debe tener al menos 6 caracteres.');
        redirect('register.php');
    }

    $check = db()->prepare('SELECT id FROM usuarios WHERE correo = :correo LIMIT 1');
    $check->execute(['correo' => $correo]);

    if ($check->fetch()) {
        setFlash('flash_error', 'Ese correo ya esta registrado.');
        redirect('register.php');
    }

    $stmt = db()->prepare('INSERT INTO usuarios (nombre, apellido, correo, contrasena, es_admin) VALUES (:nombre, :apellido, :correo, :contrasena, 0)');
    $stmt->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'correo' => $correo,
        'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
    ]);

    setFlash('flash_success', 'Cuenta creada. Ahora puedes iniciar sesión.');
    redirect('login.php');
}

renderHeader('Crear cuenta');
?>

<section class="panel narrow">
    <h2>Crear cuenta</h2>
    <form method="post" class="form-grid">
        <label>Nombre</label>
        <input type="text" name="nombre" required>

        <label>Apellido</label>
        <input type="text" name="apellido" required>

        <label>Correo</label>
        <input type="email" name="correo" required>

        <label>Contraseña</label>
        <input type="password" name="contrasena" required>

        <label>Confirmar contraseña</label>
        <input type="password" name="confirmar_contrasena" required>

        <button type="submit">Registrar</button>
    </form>
</section>

<?php renderFooter(); ?>
