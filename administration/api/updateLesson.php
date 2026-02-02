<?php
require '../db.php';
$data = json_decode(file_get_contents('php://input'), true);
$teacherId = $_SESSION['teacher_id'];

// Conflict check (ignore self)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons WHERE teacher_id=? AND id!=? AND (? < end_time AND ? > start_time)");
$stmt->execute([$teacherId, $data['id'], $data['start'], $data['end']]);

if ($stmt->fetchColumn() > 0) {
echo json_encode(['success'=>false,'error'=>'Conflict detected']);
exit;
}

$update = $pdo->prepare("UPDATE lessons SET start_time=?, end_time=? WHERE id=? AND teacher_id=?");
$update->execute([$data['start'], $data['end'], $data['id'], $teacherId]);

echo json_encode(['success'=>true]);