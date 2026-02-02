<?php
#require '../db.php';
$teacherId = $_SESSION['teacher_id'];


$sql = "SELECT l.id, l.start_time AS start, l.end_time AS end, CONCAT(s.name,' - ',l.lesson_type) AS title FROM lessons l JOIN students s ON s.id = l.student_id WHERE l.teacher_id = ? AND l.status='scheduled'";


#$stmt = $pdo->prepare($sql);
#$stmt->execute([$teacherId]);
#echo json_encode($stmt->fetchAll());

echo "[{\"id\": \"0\", \"start\": \"2023-09-01T10:00:00\", \"end\": \"2023-09-01T11:00:00\", \"title\": \"Keith Stanek - Piano\"}]";