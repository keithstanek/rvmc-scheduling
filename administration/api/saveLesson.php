<?php
require '../db.php';
$data = json_decode(file_get_contents('php://input'), true);
$teacherId = $_SESSION['teacher_id'];


// Conflict detection
$conflict = $pdo->prepare(
"SELECT COUNT(*) FROM lessons
WHERE teacher_id=? AND status='scheduled'
AND (? < end_time AND ? > start_time)"
);
$conflict->execute([$teacherId, $data['start'], $data['end']]);


if ($conflict->fetchColumn() > 0) {
echo json_encode(['success'=>false,'error'=>'Time slot already booked']);
exit;
}


if ($data['recurring']) {
for ($i=0; $i<12; $i++) { // next 12 weeks
$start = date('Y-m-d H:i:s', strtotime("+{$i} week", strtotime($data['start'])));
$end = date('Y-m-d H:i:s', strtotime("+{$i} week", strtotime($data['end'])));


$stmt = $pdo->prepare(
"INSERT INTO lessons (teacher_id, student_id, lesson_type, start_time, end_time)
VALUES (?,?,?,?,?)"
);
$stmt->execute([$teacherId, $data['student_id'], $data['lesson_type'], $start, $end]);
}
} else {
$stmt = $pdo->prepare(
"INSERT INTO lessons (teacher_id, student_id, lesson_type, start_time, end_time)
VALUES (?,?,?,?,?)"
);
$stmt->execute([$teacherId, $data['student_id'], $data['lesson_type'], $data['start'], $data['end']]);
}


echo json_encode(['success'=>true]);