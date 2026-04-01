<?php

require_once __DIR__ . '/../includes/layout.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$product = [
    'nombre_producto' => '',
    'descripcion' => '',
    'categoria' => '',
    'precio_producto' => '',
    'imagen' => '',
    'activo' => 1,
];

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM productos WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $found = $stmt->fetch();
    if ($found) {
        $product = $found;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_producto'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $precio = (float)($_POST['precio_producto'] ?? 0);
    $imagen = trim($_POST['imagen'] ?? '');
    $activo = isset($_POST['activo']) ? 1 : 0;

    if ($id > 0) {
        $update = db()->prepare('UPDATE productos SET nombre_producto = :nombre, descripcion = :descripcion, categoria = :categoria, precio_producto = :precio, imagen = :imagen, activo = :activo WHERE id = :id');
        $update->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'precio' => $precio,
            'imagen' => $imagen,
            'activo' => $activo,
            'id' => $id,
        ]);
        setFlash('flash_success', 'Producto actualizado.');
    } else {
        $insert = db()->prepare('INSERT INTO productos (nombre_producto, descripcion, categoria, precio_producto, imagen, activo) VALUES (:nombre, :descripcion, :categoria, :precio, :imagen, :activo)');
        $insert->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'precio' => $precio,
            'imagen' => $imagen,
            'activo' => $activo,
        ]);
        setFlash('flash_success', 'Producto creado.');
    }

    redirect('products.php');
}

renderHeader($id > 0 ? 'Editar producto' : 'Agregar producto', '../');
?>

<section class="panel narrow">
    <h2><?= $id > 0 ? 'Editar producto' : 'Agregar producto' ?></h2>

    <form method="post" class="form-grid">
        <label>Nombre del producto</label>
        <input type="text" name="nombre_producto" value="<?= e($product['nombre_producto']) ?>" required>

        <label>Descripcion</label>
        <textarea name="descripcion" rows="4"><?= e($product['descripcion'] ?? '') ?></textarea>

        <label>Categoria</label>
        <input type="text" name="categoria" value="<?= e($product['categoria'] ?? '') ?>">

        <label>Precio</label>
        <input type="number" name="precio_producto" step="0.01" min="0" value="<?= e((string)$product['precio_producto']) ?>" required>

        <label>Ruta o URL de imagen</label>
        <input type="text" name="imagen" value="<?= e($product['imagen'] ?? '') ?>" placeholder="assets/pc1.jpg o https://...">

        <label><input type="checkbox" name="activo" <?= (int)$product['activo'] === 1 ? 'checked' : '' ?>> Activo</label>

        <button type="submit">Guardar</button>
    </form>
</section>

<?php renderFooter(); ?>
