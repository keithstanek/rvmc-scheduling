<?php
require dirname(__FILE__) . "/../../classes/all_classes_include.php";

$studentDao = new StudentDao();
$students = $studentDao->getAllstudents();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $student = $studentDao->getStudentById($_GET['id']);
        if ($student) {
            echo json_encode(['student' => $student]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Student not found']);
        }
        exit;
    } else {
        $students = $studentDao->getAllStudents();
        if ($students) {
            echo json_encode(['students' => $students]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Students not found']);
        }
        exit;
    }
}

$data = json_decode(file_get_contents('php://input'), true);
$student = new Student($data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$student) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }
    $result = $studentDao->insert($student);
    echo json_encode(['message' => $result]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!$student || !isset($student->id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON or missing id']);
        exit;
    }
    $result = $studentDao->update($student);
    echo json_encode(['message' => $result]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!$student || !isset($student->id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON or missing id']);
        exit;
    }
    $result = $studentDao->delete($student);
    echo json_encode(['message' => $result]);
    exit;
}
