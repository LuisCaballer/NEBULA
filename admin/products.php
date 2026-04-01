<?php

require_once __DIR__ . '/../includes/layout.php';
requireAdmin();

$stmt = db()->query('SELECT id, nombre_producto, categoria, precio_producto, activo FROM productos ORDER BY id DESC');
$products = $stmt->fetchAll();

renderHeader('Gestion de productos', '../');
?>

<section class="panel">
    <h2>Productos</h2>
    <a class="btn-link" href="product_form.php">Agregar producto</a>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= (int)$product['id'] ?></td>
                        <td><?= e($product['nombre_producto']) ?></td>
                        <td><?= e($product['categoria'] ?? 'General') ?></td>
                        <td>$<?= number_format((float)$product['precio_producto'], 2) ?></td>
                        <td><?= (int)$product['activo'] === 1 ? 'Activo' : 'Inactivo' ?></td>
                        <td>
                            <a class="btn-link" href="product_form.php?id=<?= (int)$product['id'] ?>">Editar</a>
                            <a class="btn-link danger" href="product_delete.php?id=<?= (int)$product['id'] ?>" onclick="return confirm('Seguro que deseas eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php renderFooter(); ?>
