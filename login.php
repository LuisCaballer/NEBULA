<?php

require_once __DIR__ . '/includes/layout.php';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $stmt = db()->prepare('SELECT id, nombre, apellido, correo, contrasena, es_admin FROM usuarios WHERE correo = :correo LIMIT 1');
    $stmt->execute(['correo' => $correo]);
    $user = $stmt->fetch();

    if ($user && password_verify($contrasena, $user['contrasena'])) {
        unset($user['contrasena']);
        $_SESSION['user'] = $user;
        setFlash('flash_success', 'Bienvenido, ' . $user['nombre'] . '.');
        redirect('index.php');
    }

    setFlash('flash_error', 'Credenciales invalidas.');
    redirect('login.php');
}

renderHeader('Iniciar sesión');
?>

<section class="panel narrow">
    <h2>Iniciar sesión</h2>
    <form method="post" class="form-grid">
        <label>Correo</label>
        <input type="email" name="correo" required>

        <label>Contraseña</label>
        <input type="password" name="contrasena" required>

        <button type="submit">Entrar</button>
    </form>
</section>

<?php renderFooter(); ?>
