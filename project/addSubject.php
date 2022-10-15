<?php
require_once("./libs/Subject.php");
require_once("./libs/Db.php");

$postData = json_decode(file_get_contents('php://input'), true);

session_start();
if (isset($_SESSION['username']) == false) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized user!']);
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $subject = new Subject($postData);
    
        $result = (new Db())->addSubject($subject);
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'error' => "Invalid request method!"]);
    }
}

?>