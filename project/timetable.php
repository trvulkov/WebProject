<?php
require_once("./libs/Db.php");

$postData = json_decode(file_get_contents('php://input'), true);

session_start();
if (isset($_SESSION['username']) == false) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized user!']);
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $db = new Db();
        
        $programmes = array_map(function($x) { return $x['programme']; }, $db->fetchPlanProgrammes());
        $semesters = array_map(function($x) { return $x['semester']; }, $db->fetchPlanSemesters());
    
        echo json_encode(['success' => true, 'programmes' => $programmes, 'semesters' => $semesters]);
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $programme = isset($postData['programme']) ? $postData['programme'] : "";
        $semester = isset($postData['semester']) ? $postData['semester'] : "";
    
        $result = (new Db())->fetchPlan($programme, $semester);
        $subjects = array_map(function($x) { return $x['subject']; }, $result);
    
        echo json_encode(['success' => true, 'subjects' => $subjects]);
    } else {
        echo json_encode(['success' => false, 'error' => "Invalid request method!"]);
    }    
}

?>