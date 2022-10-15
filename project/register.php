<?php
require_once("./libs/User.php");
require_once("./libs/Db.php");

session_start();
$postData = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User($postData);

    $result = (new Db())->addUser($user);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => "Invalid request method!"]);
}


?>