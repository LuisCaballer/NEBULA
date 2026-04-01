<?php

require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $database = new NebulaDatabase();
        $pdo = $database->connect();
    }

    return $pdo;
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return currentUser() !== null;
}

function isAdmin(): bool
{
    $user = currentUser();
    return $user !== null && (int)$user['es_admin'] === 1;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        $_SESSION['flash_error'] = 'Debes iniciar sesión para continuar.';
        redirect('login.php');
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        $_SESSION['flash_error'] = 'No tienes permisos para esta sección.';
        redirect('../index.php');
    }
}

function setFlash(string $key, string $message): void
{
    $_SESSION[$key] = $message;
}

function getFlash(string $key): ?string
{
    if (!isset($_SESSION[$key])) {
        return null;
    }

    $message = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $message;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
