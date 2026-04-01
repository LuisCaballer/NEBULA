<?php

require_once __DIR__ . '/includes/auth.php';

session_unset();
session_destroy();

session_start();
setFlash('flash_success', 'Sesión cerrada correctamente.');
redirect('index.php');
