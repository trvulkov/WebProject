<?php
require_once("./libs/Db.php");

session_start();

$postData = json_decode(file_get_contents('php://input'), true);
$result = (new Db())->checkLogin($postData);

if ($result['success']) {
    $_SESSION['username'] = $result['user']['username'];
}
echo json_encode($result);

?>