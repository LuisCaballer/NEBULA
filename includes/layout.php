<?php

require_once __DIR__ . '/auth.php';

function renderHeader(string $title = 'NEBULA GAMING', string $basePath = ''): void
{
    $user = currentUser();
    $cartCount = 0;

    if ($user) {
        $stmt = db()->prepare('SELECT COALESCE(SUM(cantidad_producto), 0) AS total FROM carrito WHERE id_usuario = :id_usuario AND comprado = 0');
        $stmt->execute(['id_usuario' => $user['id']]);
        $cartCount = (int)$stmt->fetchColumn();
    }

    echo '<!DOCTYPE html>';
    echo '<html lang="es">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . e($title) . '</title>';
    echo '<link rel="icon" type="image/svg+xml" href="' . $basePath . 'assets/favicon.svg">';
    echo '<link rel="alternate icon" type="image/png" href="' . $basePath . 'assets/gamepad.png">';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Bebas+Neue&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="' . $basePath . 'styles.css">';
    echo '</head>';
    echo '<body>';
    echo '<header class="topbar">';
    echo '<a class="brand" href="' . $basePath . 'index.php"><span>NEBULA</span> Gaming</a>';
    echo '<nav class="menu">';
    echo '<a href="' . $basePath . 'index.php">Productos</a>';
    echo '<a href="' . $basePath . 'cart.php">Carrito (' . $cartCount . ')</a>';

    if ($user) {
        echo '<a href="' . $basePath . 'profile.php">Mi perfil</a>';
        if ((int)$user['es_admin'] === 1) {
            echo '<a href="' . $basePath . 'admin/index.php">Admin</a>';
        }
        echo '<a href="' . $basePath . 'logout.php">Salir</a>';
    } else {
        echo '<a href="' . $basePath . 'login.php">Iniciar sesión</a>';
        echo '<a href="' . $basePath . 'register.php">Crear cuenta</a>';
    }

    echo '</nav>';
    echo '</header>';
    echo '<main class="main-content">';

    $success = getFlash('flash_success');
    $error = getFlash('flash_error');

    if ($success !== null) {
        echo '<div class="alert success">' . e($success) . '</div>';
    }

    if ($error !== null) {
        echo '<div class="alert error">' . e($error) . '</div>';
    }
}

function renderFooter(): void
{
    echo '</main>';
    echo '<footer class="footer">NEBULA GAMING - Proyecto e-commerce de PCs</footer>';
    echo '</body>';
    echo '</html>';
}
