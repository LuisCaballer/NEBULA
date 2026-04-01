<?php

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sqlFile = __DIR__ . '/config/schema.sql';
        $sql = file_get_contents($sqlFile);

        $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $queries = preg_split('/;\s*\n/', $sql);

        foreach ($queries as $query) {
            $query = trim($query);
            if ($query === '') {
                continue;
            }
            $pdo->exec($query);
        }

        $message = 'Instalacion completada. Ya puedes usar la tienda.';
    } catch (Throwable $e) {
        $error = 'Error al instalar: ' . $e->getMessage();

        if (strpos($e->getMessage(), '[2002]') !== false) {
            $error .= ' | Verifica que MySQL/MariaDB este iniciado en el puerto 3306.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup NEBULA</title>
    <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
    <link rel="alternate icon" type="image/png" href="assets/gamepad.png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<main class="main-content">
    <section class="panel narrow">
        <h2>Instalador de Base de Datos</h2>
        <p>Este paso crea las tablas de usuarios, productos, carrito y factura.</p>

        <?php if ($message): ?>
            <div class="alert success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <p>Admin inicial: admin@nebula.com / Admin123!</p>
            <a class="btn-link" href="index.php">Ir a la tienda</a>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!$message): ?>
            <form method="post">
                <button type="submit">Instalar BD</button>
            </form>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
