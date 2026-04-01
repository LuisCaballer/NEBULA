<?php

function connectDatabase(): \PDO
{
    $host = 'localhost';
    $dbName = 'nebula_gaming';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

    $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new \PDO($dsn, $username, $password, $options);
}
