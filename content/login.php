<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if(empty($_POST['mail'])) {
        $errors['mail'] = 'Поле обязательно для заполнения';
    }
    if(empty($_POST['password'])) {
        $errors['password'] = 'Поле обязательно для заполнения';
    }

    if(empty($errors)) {
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        $config = include('config/config.php');
        // Подключение к базе данных: host, dbname, username, password
        try {
            $dsn = 'pgsql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
            $pdo = new PDO($dsn, $config['username'], $config['password']);
        } catch (PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }

        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :mail');
        $stmt->execute(['mail' => $mail]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            $errors['mail'] = 'Неправильное имя пользователя или пароль';
        } else {
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(['success' => true, 'name' => $user['name']]);
            exit;
        }
    }

    echo json_encode(['errors' => $errors, 'success' => false]);
}