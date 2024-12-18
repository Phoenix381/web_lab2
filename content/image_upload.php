<?php
session_start();

$errors = array();
// validating  file
if ($_FILES['image']['error'] == 0) {
    $file_name = $_FILES['image']['name'];   
    $file_size = $_FILES['image']['size'];
    $file_type = $_FILES['image']['type'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";   
    }

    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }

    $types = array('image/jpeg', 'image/png');

    if (in_array($file_type, $types) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }
}

if (empty($errors) == true) {
    $path  = pathinfo($_FILES['image']['name']);
    $ext   = $path['extension'] ?? '';
    $new_name = uniqid() . '.' . $ext;
    try {
        move_uploaded_file($file_tmp, 'uploaded/' . $new_name);
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
    
    $config = include('config/config.php');
    // Подключение к базе данных: host, dbname, username, password
    try {
        $dsn = 'pgsql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
        $pdo = new PDO($dsn, $config['username'], $config['password']);
    } catch (PDOException $e) {
        die('Подключение не удалось: ' . $e->getMessage());
    }

    $stmt = $pdo->prepare('INSERT INTO posts (content, direct_link, created_at, author_id) VALUES (:path, :direct_link, NOW(), :author_id)');

    $stmt->bindParam(':path', $new_name, PDO::PARAM_STR);
    $direct_link = empty($_POST['check']) ? false : true;
    $stmt->bindParam(':direct_link', $direct_link, PDO::PARAM_BOOL);
    $author = intval($_SESSION['user_id']);
    $stmt->bindParam(':author_id', $author, PDO::PARAM_INT);

    $stmt->execute();
}

if (!empty($errors)) {
    // var_dump($errors);
    http_response_code(400);
    echo json_encode(['error_text' => end($errors), 'success' => false]);
} else {
    $id = $pdo->lastInsertId();
    header('Location: /image.php?id=' . $id);
}