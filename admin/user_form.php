<?php

require_once __DIR__ . '/../includes/layout.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$userData = [
    'nombre' => '',
    'apellido' => '',
    'correo' => '',
    'es_admin' => 0,
];

if ($id > 0) {
    $stmt = db()->prepare('SELECT id, nombre, apellido, correo, es_admin FROM usuarios WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $userData = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $esAdmin = isset($_POST['es_admin']) ? 1 : 0;
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($id > 0) {
        if ($contrasena !== '' || $confirmar !== '') {
            if ($contrasena !== $confirmar) {
                setFlash('flash_error', 'Las contraseñas no coinciden.');
                redirect('user_form.php?id=' . $id);
            }

            $update = db()->prepare('UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo = :correo, es_admin = :es_admin, contrasena = :contrasena WHERE id = :id');
            $update->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo' => $correo,
                'es_admin' => $esAdmin,
                'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
                'id' => $id,
            ]);
        } else {
            $update = db()->prepare('UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo = :correo, es_admin = :es_admin WHERE id = :id');
            $update->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo' => $correo,
                'es_admin' => $esAdmin,
                'id' => $id,
            ]);
        }

        if (currentUser()['id'] === $id) {
            $_SESSION['user']['nombre'] = $nombre;
            $_SESSION['user']['apellido'] = $apellido;
            $_SESSION['user']['correo'] = $correo;
            $_SESSION['user']['es_admin'] = $esAdmin;
        }

        setFlash('flash_success', 'Usuario actualizado.');
    } else {
        if ($contrasena === '' || $confirmar === '') {
            setFlash('flash_error', 'La contraseña es obligatoria para crear usuarios.');
            redirect('user_form.php');
        }

        if ($contrasena !== $confirmar) {
            setFlash('flash_error', 'Las contraseñas no coinciden.');
            redirect('user_form.php');
        }

        $insert = db()->prepare('INSERT INTO usuarios (nombre, apellido, correo, contrasena, es_admin) VALUES (:nombre, :apellido, :correo, :contrasena, :es_admin)');
        $insert->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
            'es_admin' => $esAdmin,
        ]);
        setFlash('flash_success', 'Usuario creado.');
    }

    redirect('users.php');
}

renderHeader($id > 0 ? 'Editar usuario' : 'Agregar usuario', '../');
?>

<section class="panel narrow">
    <h2><?= $id > 0 ? 'Editar usuario' : 'Agregar usuario' ?></h2>

    <form method="post" class="form-grid">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= e($userData['nombre']) ?>" required>

        <label>Apellido</label>
        <input type="text" name="apellido" value="<?= e($userData['apellido']) ?>" required>

        <label>Correo</label>
        <input type="email" name="correo" value="<?= e($userData['correo']) ?>" required>

        <label>Contraseña <?= $id > 0 ? '(opcional)' : '' ?></label>
        <input type="password" name="contrasena" <?= $id > 0 ? '' : 'required' ?>>

        <label>Confirmar contraseña <?= $id > 0 ? '(opcional)' : '' ?></label>
        <input type="password" name="confirmar_contrasena" <?= $id > 0 ? '' : 'required' ?>>

        <label><input type="checkbox" name="es_admin" <?= (int)$userData['es_admin'] === 1 ? 'checked' : '' ?>> Administrador</label>

        <button type="submit">Guardar usuario</button>
    </form>
</section>

<?php renderFooter(); ?>
