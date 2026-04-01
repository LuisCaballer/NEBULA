<?php

require_once __DIR__ . '/../includes/layout.php';
requireAdmin();

$stmt = db()->query('SELECT id, nombre, apellido, correo, es_admin FROM usuarios ORDER BY id DESC');
$users = $stmt->fetchAll();

renderHeader('Gestion de usuarios', '../');
?>

<section class="panel">
    <h2>Usuarios</h2>
    <a class="btn-link" href="user_form.php">Agregar usuario</a>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $usr): ?>
                    <tr>
                        <td><?= (int)$usr['id'] ?></td>
                        <td><?= e($usr['nombre'] . ' ' . $usr['apellido']) ?></td>
                        <td><?= e($usr['correo']) ?></td>
                        <td><?= (int)$usr['es_admin'] === 1 ? 'Administrador' : 'Usuario' ?></td>
                        <td>
                            <a class="btn-link" href="user_form.php?id=<?= (int)$usr['id'] ?>">Editar</a>
                            <a class="btn-link danger" href="user_delete.php?id=<?= (int)$usr['id'] ?>" onclick="return confirm('Seguro que deseas eliminar este usuario?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php renderFooter(); ?>
