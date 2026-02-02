<?php
require dirname(__FILE__) . "/../../classes/all_classes_include.php";

$courseDao = new CourseDao();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $course = $courseDao->getCourseById($_GET['id']);
        if ($course) {
            echo json_encode(['course' => $course]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
        }
        exit;
    } else {
        $courses = $courseDao->getAllCourses();
        if ($courses) {
            echo json_encode(['courses' => $courses]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Courses not found']);
        }
        exit;
    }
}

$data = json_decode(file_get_contents('php://input'), true);
$course = new Course($data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$course) {
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

