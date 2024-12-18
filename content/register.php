<?php
session_start();
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config = include('config/config.php');
    // Подключение к базе данных: host, dbname, username, password
    try {
        $dsn = 'pgsql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
        $pdo = new PDO($dsn, $config['username'], $config['password']);
    } catch (PDOException $e) {
        die('Подключение не удалось: ' . $e->getMessage());
    }

    // validating
    $errors = [];
    $error_text = "";

    // validating name
    if (empty($_POST['name'])) {
        $errors['name'] = 'Это поле обязательно';
    } else {
        // check length
        if (strlen($_POST['name']) < 8) {
            $errors['name'] = 'Минимальная длина имени 8 символов';
        }
    }

    // validating email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Это поле обязательно';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Неверный формат почты';
        }
    }

    // validating phone
    if (empty($_POST['phone'])) {
        $errors['phone'] = 'Это поле обязательно';
    } else {
        // validating with regex
        $pattern = '/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/';
        if (!preg_match($pattern, $_POST['phone'])) {
            $errors['phone'] = 'Неверный формат номера';
        }
        
    }

    // validating password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Это поле обязательно';   
    } else {
        // 8 or more characters, at least one lowercase letter and one number
        $pass_pattern = '/^(?=.*[a-z])(?=.*\d)[a-zA-Z\d]{8,}$/';
        if (!preg_match($pass_pattern, $_POST['password'])) {
            $errors['password'] = 'Требуется хотя бы одна строчная буква и цифра';
        }

        // 8 or more characters
        if (strlen($_POST['password']) < 8) {
            $errors['password'] = 'Минимальная длина пароля 8 символов';
        }
    }

    // validating confirm password
    if (empty($_POST['password_confirm'])) {
        $errors['password_confirm'] = 'Это поле обязательно';
    } else {
        if ($_POST['password'] !== $_POST['password_confirm']) { 
            $errors['password_confirm'] = 'Пароли не совпадают';
        }
    }

    // validating checkbox
    if (empty($_POST['check']) || $_POST['check'] != true) {
        $errors['check'] = 'Необходимо принять условия';
    }

    if(!empty($errors)) {
        $error_text = "Ошибка валидации";
    }

    // check if user exists
    $user = "SELECT * FROM users WHERE email = :email";
    $query = $pdo->prepare($user);
    $query->bindParam( ':email', $_POST['email'], PDO::PARAM_STR);
    $query->execute();
    if ($query->rowCount() > 0) {
        $error_text = 'Пользователь с таким адресом уже зарегистрирован';
    }

    if($error_text != "") {
        http_response_code(400);
        echo json_encode(['error_text' => $error_text, 'success' => false]);
        exit;   
    }

    // if any element in errors is not 1
    // foreach ($errors as $key => $value) {
    //     if ($value !== 1) {
    //         http_response_code(400);
    //         echo json_encode(['errors' => $errors, 'success' => false]);
    //         exit;
    //     }
    // }

    $user = "INSERT INTO users (name,email,phone,password) VALUES (:name, :email, :phone, :password)";
    $query = $pdo->prepare($user);

    $query->bindParam( ':name', $_POST['name'], PDO::PARAM_STR);
    $query->bindParam( ':email', $_POST['email'], PDO::PARAM_STR);
    $query->bindParam( ':phone', $_POST['phone'], PDO::PARAM_STR);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query->bindParam( ':password', $pass, PDO::PARAM_STR);

    $query->execute();

    $_SESSION['name'] = $_POST['name'];
    
    http_response_code(200);
    echo json_encode(['success' => true, 'name' => $_POST['name']]);
    exit;
}