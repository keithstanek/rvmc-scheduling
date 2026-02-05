<?php
require dirname(__FILE__) . "/../../classes/all_classes_include.php";
/**
 * Lesson Management API
 * Handles CRUD operations for music lessons
 */

// Set headers for JSON response and CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection
function getDBConnection() {
    try {
        return DbUtil::getConnection();
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed', 'message' => $e->getMessage()]);
        exit();
    }
}

// Validate lesson data
function validateLessonData($data, $isUpdate = false) {
    $errors = [];
    
    if (!$isUpdate || isset($data['student_id'])) {
        if (empty($data['student_id']) || !is_numeric($data['student_id'])) {
            $errors[] = 'Valid student_id is required';
        }
    }
    
    if (!$isUpdate || isset($data['course_id'])) {
        if (empty($data['course_id']) || !is_numeric($data['course_id'])) {
            $errors[] = 'Valid course_id is required';
        }
    }
    
    if (!$isUpdate || isset($data['date'])) {
        if (empty($data['date']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date'])) {
            $errors[] = 'Valid date (YYYY-MM-DD) is required';
        }
    }
    
    if (!$isUpdate || isset($data['time'])) {
        if (empty($data['time']) || !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $data['time'])) {
            $errors[] = 'Valid time (HH:MM or HH:MM:SS) is required';
        }
    }

    if (!$isUpdate || isset($data['duration'])) {
        if (empty($data['duration']) || !is_numeric($data['duration']) || $data['duration'] < 15) {
            $errors[] = 'Duration must be at least 15 minutes';
        }
    }
    
    if (!$isUpdate || isset($data['frequency'])) {
        $validFrequencies = ['single', 'weekly', 'biweekly', 'monthly'];
        if (empty($data['frequency']) || !in_array($data['frequency'], $validFrequencies)) {
            $errors[] = 'Frequency must be: single, weekly, biweekly, or monthly';
        }
    }
    
    if (isset($data['payment']) && !empty($data['payment'])) {
        if (!is_numeric($data['payment']) || $data['payment'] < 0) {
            $errors[] = 'Payment amount must be a positive number';
        }
    }
    
    if (isset($data['payment_method']) && !empty($data['payment_method'])) {
        $validMethods = ['cash', 'check', 'debit', 'ach'];
        if (!in_array($data['payment_method'], $validMethods)) {
            $errors[] = 'Payment method must be: cash, check, debit, or ach';
        }
    }
    
    return $errors;
}
/**
 * Helper: Generate recurring lesson dates
 * @param string $startDate (YYYY-MM-DD)
 * @param string $frequency ('weekly', 'biweekly', 'monthly')
 * @param int $count Number of occurrences to generate
 * @return array Array of dates (YYYY-MM-DD)
 */
function generateRecurringDates($startDate, $frequency, $count = 100) {
    $dates = [];
    $date = new DateTime($startDate);

    for ($i = 0; $i < $count; $i++) {
        $dates[] = $date->format('Y-m-d');
        switch ($frequency) {
            case 'weekly':
                $date->modify('+1 week');
                break;
            case 'biweekly':
                $date->modify('+2 weeks');
                break;
            case 'monthly':
                $date->modify('+1 month');
                break;
            default:
                // 'single' or unknown, only one occurrence
                return [$startDate];
        }
    }
    return $dates;
}

// CREATE - Add new lesson
function createLesson($pdo, $data) {
    $errors = validateLessonData($data);
    if (!empty($errors)) {
        http_response_code(400);
        return ['error' => 'Validation failed', 'details' => $errors];
    }
    
    try {
        $sql = "INSERT INTO lesson (
                    series_id, teacher_id, student_id, course_id, lesson_date, lesson_time,  
                    duration, frequency, notes, payment_amount, payment_method
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

        $lessonDateTime = new DateTime($data['date']);
        $currentDate = clone $lessonDateTime;
        $seriesId = uniqid();

        for ($i = 0; $i < 10; $i++) {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $seriesId,
                $data['teacher_id'],
                $data['student_id'],
                $data['course_id'],
                $currentDate->format('Y-m-d H:i:s'),
                $data['time'],
                $data['duration'],
                $data['frequency'],
                $data['notes'] ?? null,
                !empty($data['payment']) ? $data['payment'] : 0.00,
                $data['payment_method'] ?? null
            ]);

            if ($data['frequency'] === 'single') {
                break;
            }

            $currentDate = nextLessonDate($currentDate, $data['frequency']);
        }
                
        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Lesson created successfully',
        ];
        
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to create lesson', 'message' => $e->getMessage()];
    }
}

function nextLessonDate(DateTime $date, string $frequency): DateTime {
    $new = clone $date;

    switch ($frequency) {
        case 'weekly':
            $new->modify('+1 week');
            break;
        case 'biweekly':
            $new->modify('+2 weeks');
            break;
        case 'monthly':
            $new->modify('+1 month');
            break;
        default: // single
            break;
    }

    return $new;
}

// READ - Get lessons for teacher
function getLessons($pdo, $teacherId, $filters = []) {
    try {
        $sql = "SELECT 
                    l.id,
                    l.series_id,
                    l.teacher_id,
                    l.student_id,
                    l.course_id,
                    l.lesson_date as date,
                    l.lesson_time as time,
                    l.duration,
                    l.frequency,
                    l.notes,
                    l.payment_amount as payment,
                    l.payment_method,
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
                    c.name,
                    l.created_at,
                    l.updated_at
                FROM lesson l
                INNER JOIN student s ON l.student_id = s.id
                INNER JOIN course c ON l.course_id = c.id
                INNER JOIN teacher t ON l.teacher_id = t.id ";

        if (!empty($filters['teacher_id'])) {
            $sql .= "WHERE l.teacher_id = :teacher_id";
            $params[':teacher_id'] = $filters['teacher_id'];
        }
        
        // Add filters if provided
        if (!empty($filters['start_date'])) {
            $sql .= ((stripos($sql, 'WHERE') === false) ? " WHERE" : " AND") . " l.lesson_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= ((stripos($sql, 'WHERE') === false) ? " WHERE" : " AND") . " l.lesson_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }
        
        if (!empty($filters['student_id'])) {
            $sql .= ((stripos($sql, 'WHERE') === false) ? " WHERE" : " AND") . " l.student_id = :student_id";
            $params[':student_id'] = $filters['student_id'];
        }
        
        $sql .= " ORDER BY l.lesson_date ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $lessons = $stmt->fetchAll();
        
        return [
            'success' => true,
            'lessons' => $lessons
        ];
        
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to fetch lessons', 'message' => $e->getMessage()];
    }
}

// READ - Get single lesson by ID
function getLessonById($pdo, $lessonId) {
    try {
        $sql = "SELECT 
                    l.id,
                    l.series_id,
                    l.teacher_id,
                    l.student_id,
                    l.course_id,
                    l.lesson_date as date,
                    l.lesson_time as time,
                    l.duration,
                    l.frequency,
                    l.notes,
                    l.payment_amount as payment,
                    l.payment_method,
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name,                    
                    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
                    c.name,
                    l.created_at,
                    l.updated_at
                FROM lesson l
                INNER JOIN student s ON l.student_id = s.id
                INNER JOIN course c ON l.course_id = c.id
                INNER JOIN teacher t ON l.teacher_id = t.id
                WHERE l.id = :lesson_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lesson_id' => $lessonId
        ]);
        
        $lesson = $stmt->fetch();
        
        if (!$lesson) {
            http_response_code(404);
            return null;
        }
        
        return $lesson;
        
    } catch (PDOException $e) {
        http_response_code(500);
        return null;
    }
}

// Get Lessons by Teacher ID
function getLessonsByTeacherId($pdo, $teacherId) {
    try {
        $sql = "SELECT 
                    l.id,
                    l.series_id,
                    l.teacher_id,
                    l.student_id,
                    l.course_id,
                    l.lesson_date as date,
                    DATE_FORMAT(l.lesson_time, '%h:%i %p') AS time,
                    l.duration,
                    l.frequency,
                    l.notes,
                    l.payment_amount as payment,
                    l.payment_method,
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                    CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
                    c.name,
                    l.created_at,
                    l.updated_at
                FROM lesson l
                INNER JOIN student s ON l.student_id = s.id
                INNER JOIN course c ON l.course_id = c.id
                INNER JOIN teacher t ON l.teacher_id = t.id
                WHERE l.teacher_id = :teacher_id
                ORDER BY l.lesson_date ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':teacher_id' => $teacherId]);
        
        $lessons = $stmt->fetchAll();
        
        if (!$lessons) {
            http_response_code(404);
            return null;
        }
        
        return $lessons;
        
    } catch (PDOException $e) {
        http_response_code(500);
        return null;
    }
}

// UPDATE - Modify existing lesson
function updateLesson($pdo, $data) {
    // First verify the lesson belongs to this teacher
    $existing = getLessonById($pdo, $data['id']);
    if (!$existing) {
        http_response_code(404);
        return ['error' => 'Lesson not found or access denied'];
    }
    
    $errors = validateLessonData($data, true);
    if (!empty($errors)) {
        http_response_code(400);
        return ['error' => 'Validation failed', 'details' => $errors];
    }

    $params = [];
    $applyToAll = isset($data['apply_to_all']) ? (bool)$data['apply_to_all'] : false;
    
    try {
        // Build dynamic UPDATE query based on provided fields
        $updateFields = [];
        
        if (isset($data['student_id'])) {
            $updateFields[] = "student_id = :student_id";
            $params[':student_id'] = $data['student_id'];
        }

        if (isset($data['teacher_id'])) {
            $updateFields[] = "teacher_id = :teacher_id";
            $params[':teacher_id'] = $data['teacher_id'];
        }
        
        if (isset($data['course_id'])) {
            $updateFields[] = "course_id = :course_id";
            $params[':course_id'] = $data['course_id'];
        }
        
        if (isset($data['date']) && !$applyToAll) {
            $updateFields[] = "lesson_date = :lesson_date";
            $params[':lesson_date'] = $data['date'];
        }
        
        if (isset($data['time'])) {
            $updateFields[] = "lesson_time = :lesson_time";
            $params[':lesson_time'] = $data['time'];
        }
        
        if (isset($data['duration'])) {
            $updateFields[] = "duration = :duration";
            $params[':duration'] = $data['duration'];
        }
        
        if (isset($data['frequency'])) {
            $updateFields[] = "frequency = :frequency";
            $params[':frequency'] = $data['frequency'];
        }
        
        if (isset($data['notes'])) {
            $updateFields[] = "notes = :notes";
            $params[':notes'] = $data['notes'];
        }
        
        if (isset($data['payment'])) {
            $updateFields[] = "payment_amount = :payment_amount";
            $params[':payment_amount'] = !empty($data['payment']) ? $data['payment'] : 0.00;
        }
        
        if (isset($data['payment_method'])) {
            $updateFields[] = "payment_method = :payment_method";
            $params[':payment_method'] = $data['payment_method'];
        }
        
        if (empty($updateFields)) {
            http_response_code(400);
            return ['error' => 'No fields to update'];
        }
        
        $sql = "UPDATE lesson SET " . implode(', ', $updateFields) . 
               " WHERE id = :id AND teacher_id = :teacher_id";
        
        if ($applyToAll && $existing['series_id']) {
            $params[':series_id'] = $existing['series_id'];
            $sql = "UPDATE lesson SET " . implode(', ', $updateFields) . 
               " WHERE series_id = :series_id";
        } else {
            $params[':id'] = $data['id'];
            $params[':teacher_id'] = $existing['teacher_id'];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Fetch updated lesson
        $lesson = getLessonById($pdo, $data['id']);
        
        return [
            'success' => true,
            'message' => 'Lesson updated successfully',
            'lesson' => $lesson
        ];
        
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to update lesson', 'message' => $e->getMessage()];
    }
}

// DELETE - Remove lesson
function deleteLesson($pdo, $data) {
    try {
        $existing = getLessonById($pdo, $data['id']);
        if (!$existing) {
            http_response_code(404);
            return ['error' => 'Lesson not found'];
        }

        $applyToAll = isset($data['apply_to_all']) ? (bool)$data['apply_to_all'] : false;
        if ($applyToAll && $existing['series_id']) {
            // Delete all lessons in the series
            $sql = "DELETE FROM lesson WHERE series_id = :series_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':series_id' => $existing['series_id']]);
            
            return [
                'success' => true,
                'message' => 'All lessons in the series deleted successfully'
            ];
        } else {
            $sql = "DELETE FROM lesson WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $data['id']]);
        
            return [
                'success' => true,
                'message' => 'Lesson deleted successfully'
            ];
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to delete lesson', 'message' => $e->getMessage()];
    }
}

// Main request handler
try {
    $pdo = getDBConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Get request data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Route based on HTTP method and data
    switch ($method) {
        case 'GET':
            // Get lessons with optional filters
            $filters = [
                'start_date' => $_GET['start_date'] ?? null,
                'end_date' => $_GET['end_date'] ?? null,
                'student_id' => $_GET['student_id'] ?? null,
                'teacher_id' => $_GET['teacher_id'] ?? null
            ];
            
            if (isset($_GET['id'])) {
                // Get single lesson
                $lesson = getLessonById($pdo, $_GET['id']);
                if ($lesson) {
                    echo json_encode(['success' => true, 'lesson' => $lesson]);
                } else {
                    echo json_encode(['error' => 'Lesson not found']);
                }
            } else if (isset($_GET['teacher_id'])) {
                // Get lessons by teacher ID
                $lessons = getLessonsByTeacherId($pdo, $_GET['teacher_id']);
                if ($lessons) {
                    echo json_encode(['success' => true, 'lessons' => $lessons]);
                } else {
                    echo json_encode(['error' => 'Lesson not found']);
                }
            } else {
                // Get all lessons for teacher
                $result = getLessons($pdo, $teacherId, $filters);
                echo json_encode($result);
            }
            break;
            
        case 'POST':
            // Determine if this is a save or delete based on data
            // if (empty($data['id'])) {
            //     http_response_code(400);
            //     echo json_encode(['error' => 'Lesson ID is required']);
            // } else {
                !empty($data['id'])
                    ? $result = updateLesson($pdo, $data)
                    : $result = createLesson($pdo, $data);

                echo json_encode($result);
            // }
            
            break;
            
        case 'PUT':
            // Update lesson
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Lesson ID is required']);
            } else {
                $result = updateLesson($pdo, $data);
                echo json_encode($result);
            }
            break;
            
        case 'DELETE':
            // Delete lesson
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Lesson ID is required']);
            } else {
                $result = deleteLesson($pdo, $data);
                echo json_encode($result);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
}
?>