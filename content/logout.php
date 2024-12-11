<?php 
session_start();
$_SESSION['name'] = null;
session_destroy();

header('Content-Type: application/json');

echo json_encode(array('success' => true));
http_response_code(200);
exit();