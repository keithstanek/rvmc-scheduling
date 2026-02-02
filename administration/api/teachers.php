<?php
require dirname(__FILE__) . "/../../classes/all_classes_include.php";

$teacherDao = new TeacherDao();
$teachers = $teacherDao->getAllTeachers();


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $teacher = $teacherDao->getTeacherById($_GET['id']);
        if ($teacher) {
            echo json_encode(['teacher' => $teacher]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Teacher not found']);
        }
        exit;
    } else {
        $teachers = $teacherDao->getAllTeachers();
        if ($teachers) {
            echo json_encode(['teachers' => $teachers]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Teachers not found']);
        }
        exit;
    }
}

$data = json_decode(file_get_contents('php://input'), true);
$teacher = new Teacher($data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$teacher) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }
    $result = $teacherDao->insert($teacher);
    echo json_encode(['message' => $result]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!$teacher || !isset($teacher->id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON or missing id']);
        exit;
    }
    $result = $teacherDao->update($teacher);
    echo json_encode(['message' => $result]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!$teacher || !isset($teacher->id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON or missing id']);
        exit;
    }
    $result = $teacherDao->delete($teacher);
    echo json_encode(['message' => $result]);
    exit;
}

