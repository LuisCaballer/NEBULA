<?php

require_once __DIR__ . '/includes/layout.php';

function resolveProductImage(array $product): string
{
    $image = trim((string)($product['imagen'] ?? ''));

    if ($image !== '') {
        return $image;
    }

    $catalog = strtolower(
        (string)($product['nombre_producto'] ?? '') . ' ' .
        (string)($product['descripcion'] ?? '') . ' ' .
        (string)($product['categoria'] ?? '')
    );

    if (strpos($catalog, 'rtx 4070') !== false || strpos($catalog, 'gaming') !== false) {
        return 'assets/pc-gaming.svg';
    }

    if (strpos($catalog, 'workstation') !== false || strpos($catalog, 'intel i9') !== false || strpos($catalog, 'a2000') !== false) {
        return 'assets/pc-workstation.svg';
    }

    if (strpos($catalog, 'entrada') !== false || strpos($catalog, 'ryzen 5') !== false || strpos($catalog, 'rx 6600') !== false) {
        return 'assets/pc-entry.svg';
    }

    return 'assets/pc-entry.svg';
}

$search = trim($_GET['q'] ?? '');
$category = trim($_GET['categoria'] ?? '');

$sql = 'SELECT id, nombre_producto, descripcion, categoria, precio_producto, imagen
        FROM productos
        WHERE activo = 1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (nombre_producto LIKE :search OR descripcion LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

if ($category !== '') {
    $sql .= ' AND categoria = :categoria';
    $params['categoria'] = $category;
}

$sql .= ' ORDER BY created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categoryStmt = db()->query('SELECT DISTINCT categoria FROM productos WHERE activo = 1 AND categoria IS NOT NULL AND categoria <> "" ORDER BY categoria');
$categories = $categoryStmt->fetchAll();

renderHeader('Tienda - NEBULA GAMING');
?>

<section class="hero">
    <h1>PCs de alto rendimiento para gaming y trabajo</h1>
    <p>Arma tu pedido en minutos, controla tu carrito y genera factura imprimible.</p>
</section>

<section class="panel">
    <form method="get" class="search-form">
        <input type="search" name="q" value="<?= e($search) ?>" placeholder="Buscar producto...">
        <select name="categoria">
            <option value="">Todas las categorias</option>
            <?php foreach ($categories as $cat): ?>
                <?php $value = $cat['categoria']; ?>
                <option value="<?= e($value) ?>" <?= $value === $category ? 'selected' : '' ?>><?= e($value) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Buscar</button>
    </form>
</section>

<section class="grid-products">
    <?php if (count($products) === 0): ?>
        <article class="card product-card">
            <h3>No hay productos con ese filtro</h3>
            <p>Prueba otro termino de busqueda o categoria.</p>
        </article>
    <?php endif; ?>

    <?php foreach ($products as $product): ?>
        <article class="card product-card">
            <img class="product-image" src="<?= e(resolveProductImage($product)) ?>" alt="<?= e($product['nombre_producto']) ?>">
            <h3><?= e($product['nombre_producto']) ?></h3>
            <p class="muted"><?= e($product['descripcion'] ?? 'Sin descripcion') ?></p>
            <p class="price">$<?= number_format((float)$product['precio_producto'], 2) ?></p>
            <p class="chip"><?= e($product['categoria'] ?? 'General') ?></p>

            <?php if (isLoggedIn()): ?>
                <form method="post" action="add_to_cart.php" class="inline-form">
                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1" max="20">
                    <button type="submit">Agregar al carrito</button>
                </form>
            <?php else: ?>
                <a class="btn-link" href="login.php">Inicia sesión para comprar</a>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</section>

<?php renderFooter(); ?>