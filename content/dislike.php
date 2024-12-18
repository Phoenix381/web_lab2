<?php

$config = include('config/config.php');
// Подключение к базе данных: host, dbname, username, password
try {
    $dsn = 'pgsql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
    $pdo = new PDO($dsn, $config['username'], $config['password']);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}

$stmt = $pdo->prepare('UPDATE posts SET score = score - 1 WHERE id = :id');
$stmt->execute(['id' => $_GET['id']]);

http_response_code(200);