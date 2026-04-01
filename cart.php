<?php

require_once __DIR__ . '/includes/layout.php';
requireLogin();

$user = currentUser();

$query = 'SELECT c.id, c.cantidad_producto, p.nombre_producto, p.precio_producto
          FROM carrito c
          INNER JOIN productos p ON p.id = c.id_producto
          WHERE c.id_usuario = :id_usuario AND c.comprado = 0
          ORDER BY c.created_at DESC';
$stmt = db()->prepare($query);
$stmt->execute(['id_usuario' => $user['id']]);
$items = $stmt->fetchAll();

$total = 0;
foreach ($items as $item) {
    $total += (float)$item['precio_producto'] * (int)$item['cantidad_producto'];
}

renderHeader('Carrito');
?>

<section class="panel">
    <h2>Tu carrito</h2>

    <?php if (!$items): ?>
        <p>No tienes productos agregados.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php $subtotal = (float)$item['precio_producto'] * (int)$item['cantidad_producto']; ?>
                        <tr>
                            <td><?= e($item['nombre_producto']) ?></td>
                            <td><?= (int)$item['cantidad_producto'] ?></td>
                            <td>$<?= number_format((float)$item['precio_producto'], 2) ?></td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                            <td><a class="btn-link danger" href="remove_from_cart.php?id=<?= (int)$item['id'] ?>">Eliminar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="total">Total final: $<?= number_format($total, 2) ?></p>
        <a class="btn-link" href="invoice.php">Generar factura</a>
    <?php endif; ?>
</section>

<?php renderFooter(); ?>
