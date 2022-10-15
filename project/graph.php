<?php
require_once("./libs/Subject.php");
require_once("./libs/Prerequisite.php");
require_once("./libs/Db.php");

session_start();
if (isset($_SESSION['username']) == false) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized user!']);
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $subjects = (new Db())->fetchSubjects();
        $prerequisites = (new Db())->fetchPrerequisites();
    
        $subjectNames = array_map(function($subject) { return $subject->jsonSerialize()['name']; }, $subjects);
        $prerequisiteNames = array_map(function($x) { return $x->jsonSerialize(); }, $prerequisites);
    
        echo json_encode(['success' => true, 'subjectNames' => $subjectNames, 'prerequisites' => $prerequisiteNames]);
    } else {
        echo json_encode(['success' => false, 'error' => "Invalid request method!"]);
    }    
}

?>