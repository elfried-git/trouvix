<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? intval($data['id']) : 0;

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM notifications WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID manquant ou invalide']);
}
