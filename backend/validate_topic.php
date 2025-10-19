<?php
require_once 'db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Accès refusé']);
    exit;
}
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID invalide']);
    exit;
}
$stmt = $pdo->prepare('UPDATE forum_topics SET validated = 1 WHERE id = ?');
$ok = $stmt->execute([$id]);

if ($ok) {
    echo json_encode(['success' => true]);
}else {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la validation']);
}