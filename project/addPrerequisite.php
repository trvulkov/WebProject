<?php
require_once("./libs/Subject.php");
require_once("./libs/Prerequisite.php");
require_once("./libs/Db.php");

$postData = json_decode(file_get_contents('php://input'), true);

session_start();
if (isset($_SESSION['username']) == false) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized user!']);
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $subjects = (new Db())->fetchSubjects();
    
        $names = [];
        foreach($subjects as $subject) {
            $names[] = $subject->jsonSerialize()['name'];
        }
        
        echo json_encode(['success' => true, 'names' => $names]);
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $prerequisite = new Prerequisite($postData);
    
        $result = (new Db())->addPrerequisite($prerequisite);
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'error' => "Invalid request method!"]);
    }
}

?>