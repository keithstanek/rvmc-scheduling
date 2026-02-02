<?php
require dirname(__FILE__) . "/../../classes/all_classes_include.php";
/**
 * Lesson Management API
 * Handles CRUD operations for music lessons
 */

// Start session to get logged-in teacher
session_start();

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

// Get teacher ID from session
function getTeacherId() {
    $_SESSION['teacher_id'] = "1";
    if (!isset($_SESSION['teacher_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized', 'message' => 'Teacher not logged in']);
        exit();
    }
    return $_SESSION['teacher_id'];
}

// Validate lesson data
function validateLessonData($data, $isUpdate = false) {
    $errors = [];
    
    if (!$isUpdate || isset($data['student_id'])) {
        if (empty($data['student_id']) || !is_numeric($data['student_id'])) {
            $errors[] = 'Valid student_id is required';
        }
    }
    
    if (!$isUpdate || isset($data['class_id'])) {
        if (empty($data['class_id']) || !is_numeric($data['class_id'])) {
            $errors[] = 'Valid class_id is required';
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

// Override getLessons to expand recurring lessons for calendar
function expandRecurringLessons($lessons, $filters = []) {
    $expanded = [];
    foreach ($lessons as $lesson) {
        if ($lesson['frequency'] === 'single') {
            $expanded[] = $lesson;
            continue;
        }
        // Generate future dates for recurring lessons
        $dates = generateRecurringDates($lesson['date'], $lesson['frequency'], 24);
        foreach ($dates as $date) {
            // Filter by date range if provided
            if (!empty($filters['start_date']) && $date < $filters['start_date']) continue;
            if (!empty($filters['end_date']) && $date > $filters['end_date']) continue;
            $copy = $lesson;
            $copy['date'] = $date;
            $expanded[] = $copy;
        }
    }
    return $expanded;
}

// Patch getLessons to expand recurring lessons for calendar
function getLessons($pdo, $teacherId, $filters = []) {
    try {
        $sql = "SELECT 
                    l.id,
                    l.teacher_id,
                    l.student_id,
                    l.class_id,
                    l.lesson_date as date,
                    l.lesson_time as time,
                    l.duration,
                    l.frequency,
                    l.notes,
                    l.payment_amount as payment,
                    l.payment_method,
                    l.status,
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                    c.name,
                    l.created_at,
                    l.updated_at
                FROM lesson l
                INNER JOIN student s ON l.student_id = s.id
                INNER JOIN class c ON l.class_id = c.id
                WHERE l.teacher_id = :teacher_id";
        
        $params = [':teacher_id' => $teacherId];
        
        // Only filter by student/status, not date, so we can expand recurrences
        if (!empty($filters['student_id'])) {
            $sql .= " AND l.student_id = :student_id";
            $params[':student_id'] = $filters['student_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND l.status = :status";
            $params[':status'] = $filters['status'];
        }
        $sql .= " ORDER BY l.lesson_date ASC, l.lesson_time ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $lessons = $stmt->fetchAll();

        // Expand recurring lessons for calendar
        $expanded = expandRecurringLessons($lessons, $filters);

        // Sort by date/time
        usort($expanded, function($a, $b) {
            $cmp = strcmp($a['date'], $b['date']);
            if ($cmp === 0) return strcmp($a['time'], $b['time']);
            return $cmp;
        });

        return [
            'success' => true,
            'lessons' => $expanded
        ];
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to fetch lessons', 'message' => $e->getMessage()];
    }
}
// CREATE - Add new lesson
function createLesson($pdo, $data, $teacherId) {
    $errors = validateLessonData($data);
    if (!empty($errors)) {
        http_response_code(400);
        return ['error' => 'Validation failed', 'details' => $errors];
    }
    
    try {
        $sql = "INSERT INTO lesson (
                    teacher_id, student_id, class_id, lesson_date, lesson_time, 
                    duration, frequency, notes, payment_amount, payment_method, status
                ) VALUES (
                    :teacher_id, :student_id, :class_id, :lesson_date, :lesson_time,
                    :duration, :frequency, :notes, :payment_amount, :payment_method, 'scheduled'
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':teacher_id' => $teacherId,
            ':student_id' => $data['student_id'],
            ':class_id' => $data['class_id'],
            ':lesson_date' => $data['date'],
            ':lesson_time' => $data['time'],
            ':duration' => $data['duration'],
            ':frequency' => $data['frequency'],
            ':notes' => $data['notes'] ?? null,
            ':payment_amount' => !empty($data['payment']) ? $data['payment'] : 0.00,
            ':payment_method' => $data['payment_method'] ?? null
        ]);
        
        $lessonId = $pdo->lastInsertId();
        
        // Fetch the created lesson with joined data
        $lesson = getLessonById($pdo, $lessonId, $teacherId);
        
        http_response_code(201);
        return [
            'success' => true,
            'message' => 'Lesson created successfully',
            'lesson' => $lesson
        ];
        
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to create lesson', 'message' => $e->getMessage()];
    }
}

// READ - Get lessons for teacher
// function getLessons($pdo, $teacherId, $filters = []) {
//     try {
//         $sql = "SELECT 
//                     l.id,
//                     l.teacher_id,
//                     l.student_id,
//                     l.class_id,
//                     l.lesson_date as date,
//                     l.lesson_time as time,
//                     l.duration,
//                     l.frequency,
//                     l.notes,
//                     l.payment_amount as payment,
//                     l.payment_method,
//                     l.status,
//                     CONCAT(s.first_name, ' ', s.last_name) AS student_name,
//                     c.name,
//                     l.created_at,
//                     l.updated_at
//                 FROM lesson l
//                 INNER JOIN student s ON l.student_id = s.id
//                 INNER JOIN class c ON l.class_id = c.id
//                 WHERE l.teacher_id = :teacher_id";
        
//         $params = [':teacher_id' => $teacherId];
        
//         // Add filters if provided
//         if (!empty($filters['start_date'])) {
//             $sql .= " AND l.lesson_date >= :start_date";
//             $params[':start_date'] = $filters['start_date'];
//         }
        
//         if (!empty($filters['end_date'])) {
//             $sql .= " AND l.lesson_date <= :end_date";
//             $params[':end_date'] = $filters['end_date'];
//         }
        
//         if (!empty($filters['student_id'])) {
//             $sql .= " AND l.student_id = :student_id";
//             $params[':student_id'] = $filters['student_id'];
//         }
        
//         if (!empty($filters['status'])) {
//             $sql .= " AND l.status = :status";
//             $params[':status'] = $filters['status'];
//         }
        
//         $sql .= " ORDER BY l.lesson_date ASC, l.lesson_time ASC";
        
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute($params);
        
//         return [
//             'success' => true,
//             'lessons' => $stmt->fetchAll()
//         ];
        
//     } catch (PDOException $e) {
//         http_response_code(500);
//         return ['error' => 'Failed to fetch lessons', 'message' => $e->getMessage()];
//     }
// }

// READ - Get single lesson by ID
function getLessonById($pdo, $lessonId, $teacherId) {
    try {
        $sql = "SELECT 
                    l.id,
                    l.teacher_id,
                    l.student_id,
                    l.class_id,
                    l.lesson_date as date,
                    l.lesson_time as time,
                    l.duration,
                    l.frequency,
                    l.notes,
                    l.payment_amount as payment,
                    l.payment_method,
                    l.status,
                    CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                    c.name,
                    l.created_at,
                    l.updated_at
                FROM lesson l
                INNER JOIN student s ON l.student_id = s.id
                INNER JOIN class c ON l.class_id = c.id
                WHERE l.id = :lesson_id AND l.teacher_id = :teacher_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lesson_id' => $lessonId,
            ':teacher_id' => $teacherId
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

// UPDATE - Modify existing lesson
function updateLesson($pdo, $lessonId, $data, $teacherId) {
    // First verify the lesson belongs to this teacher
    $existing = getLessonById($pdo, $lessonId, $teacherId);
    if (!$existing) {
        http_response_code(404);
        return ['error' => 'Lesson not found or access denied'];
    }
    
    $errors = validateLessonData($data, true);
    if (!empty($errors)) {
        http_response_code(400);
        return ['error' => 'Validation failed', 'details' => $errors];
    }
    
    try {
        // Build dynamic UPDATE query based on provided fields
        $updateFields = [];
        $params = [':id' => $lessonId, ':teacher_id' => $teacherId];
        
        if (isset($data['student_id'])) {
            $updateFields[] = "student_id = :student_id";
            $params[':student_id'] = $data['student_id'];
        }
        
        if (isset($data['class_id'])) {
            $updateFields[] = "class_id = :class_id";
            $params[':class_id'] = $data['class_id'];
        }
        
        if (isset($data['date'])) {
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
        
        if (isset($data['status'])) {
            $updateFields[] = "status = :status";
            $params[':status'] = $data['status'];
        }
        
        if (empty($updateFields)) {
            http_response_code(400);
            return ['error' => 'No fields to update'];
        }
        
        $sql = "UPDATE lesson SET " . implode(', ', $updateFields) . 
               " WHERE id = :id AND teacher_id = :teacher_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Fetch updated lesson
        $lesson = getLessonById($pdo, $lessonId, $teacherId);
        
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
function deleteLesson($pdo, $lessonId, $teacherId) {
    try {
        // Verify lesson belongs to teacher before deleting
        $sql = "DELETE FROM lesson WHERE id = :id AND teacher_id = :teacher_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $lessonId,
            ':teacher_id' => $teacherId
        ]);
        
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            return ['error' => 'Lesson not found or access denied'];
        }
        
        return [
            'success' => true,
            'message' => 'Lesson deleted successfully'
        ];
        
    } catch (PDOException $e) {
        http_response_code(500);
        return ['error' => 'Failed to delete lesson', 'message' => $e->getMessage()];
    }
}

// Main request handler
try {
    $pdo = getDBConnection();
    $teacherId = getTeacherId();
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
                'status' => $_GET['status'] ?? null
            ];
            
            if (isset($_GET['id'])) {
                // Get single lesson
                $lesson = getLessonById($pdo, $_GET['id'], $teacherId);
                if ($lesson) {
                    echo json_encode(['success' => true, 'lesson' => $lesson]);
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
            if (isset($data['action']) && $data['action'] === 'delete') {
                // Delete lesson
                if (empty($data['id'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Lesson ID is required']);
                } else {
                    $result = deleteLesson($pdo, $data['id'], $teacherId);
                    echo json_encode($result);
                }
            } else {
                // Create or Update lesson
                if (!empty($data['id'])) {
                    // Update existing lesson
                    $result = updateLesson($pdo, $data['id'], $data, $teacherId);
                } else {
                    // Create new lesson
                    $result = createLesson($pdo, $data, $teacherId);
                }
                echo json_encode($result);
            }
            break;
            
        case 'PUT':
            // Update lesson
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Lesson ID is required']);
            } else {
                $result = updateLesson($pdo, $data['id'], $data, $teacherId);
                echo json_encode($result);
            }
            break;
            
        case 'DELETE':
            // Delete lesson
            if (empty($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Lesson ID is required']);
            } else {
                $result = deleteLesson($pdo, $_GET['id'], $teacherId);
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