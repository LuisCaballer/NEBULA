<?php

require_once __DIR__ . '/includes/layout.php';
requireLogin();

$user = currentUser();

$query = 'SELECT c.id, c.cantidad_producto, p.nombre_producto, p.precio_producto
          FROM carrito c
          INNER JOIN productos p ON p.id = c.id_producto
          WHERE c.id_usuario = :id_usuario AND c.comprado = 0
          ORDER BY c.created_at ASC';
$stmt = db()->prepare($query);
$stmt->execute(['id_usuario' => $user['id']]);
$items = $stmt->fetchAll();

if (!$items) {
    setFlash('flash_error', 'No hay productos para facturar.');
    redirect('cart.php');
}

$total = 0;
$firstCartId = (int)$items[0]['id'];

foreach ($items as $item) {
    $total += (float)$item['precio_producto'] * (int)$item['cantidad_producto'];
}

$insertInvoice = db()->prepare('INSERT INTO factura (id_carrito, id_usuario, precio_final) VALUES (:id_carrito, :id_usuario, :precio_final)');
$insertInvoice->execute([
    'id_carrito' => $firstCartId,
    'id_usuario' => $user['id'],
    'precio_final' => $total,
]);

$invoiceId = (int)db()->lastInsertId();

$markBought = db()->prepare('UPDATE carrito SET comprado = 1 WHERE id_usuario = :id_usuario AND comprado = 0');
$markBought->execute(['id_usuario' => $user['id']]);

renderHeader('Factura');
?>

<section class="panel invoice">
    <h2>Factura #<?= $invoiceId ?></h2>
    <p><strong>Cliente:</strong> <?= e($user['nombre'] . ' ' . $user['apellido']) ?></p>
    <p><strong>Correo:</strong> <?= e($user['correo']) ?></p>
    <p><strong>Fecha:</strong> <?= date('Y-m-d H:i:s') ?></p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p class="total">Precio final: $<?= number_format($total, 2) ?></p>
    <button onclick="window.print()">Imprimir factura</button>
</section>

<?php renderFooter(); ?>
