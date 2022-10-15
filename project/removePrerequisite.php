<?php
require_once("./libs/Prerequisite.php");
require_once("./libs/Db.php");

$postData = json_decode(file_get_contents('php://input'), true);

session_start();
if (isset($_SESSION['username']) == false) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized user!']);
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $prerequisites = (new Db())->fetchPrerequisites();
    
        $names = [];
        foreach($prerequisites as $prerequisite) {
            $serialized = $prerequisite->jsonSerialize();
            $names[] = $serialized['prerequisite'] . " -> " . $serialized['subject'];
        }
        
        echo json_encode(['success' => true, 'names' => $names]);
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $subjectName = $postData['subject'];
        $prerequisiteName = $postData['prerequisite'];
    
        $result = (new Db())->removePrerequisite($subjectName, $prerequisiteName);
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'error' => "Invalid request method!"]);
    }    
}

?>