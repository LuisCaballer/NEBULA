<?php

require_once __DIR__ . '/../includes/layout.php';
requireAdmin();

$productCount = (int)db()->query('SELECT COUNT(*) FROM productos')->fetchColumn();
$userCount = (int)db()->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
$invoiceCount = (int)db()->query('SELECT COUNT(*) FROM factura')->fetchColumn();

renderHeader('Panel de administrador', '../');
?>

<section class="panel">
    <h2>Panel de administrador</h2>
    <div class="stats-grid">
        <article class="card"><h3>Productos</h3><p><?= $productCount ?></p></article>
        <article class="card"><h3>Usuarios</h3><p><?= $userCount ?></p></article>
        <article class="card"><h3>Facturas</h3><p><?= $invoiceCount ?></p></article>
    </div>
    <div class="actions">
        <a class="btn-link" href="products.php">Gestionar productos</a>
        <a class="btn-link" href="users.php">Gestionar usuarios</a>
    </div>
</section>

<?php renderFooter(); ?>
