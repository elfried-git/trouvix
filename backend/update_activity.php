<?php
session_start();
header('Content-Type: application/json');
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} elseif (isset($_SESSION['admin_id'])) {
    $userId = $_SESSION['admin_id'];
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Non connecté']);
    exit;
}
require_once 'db.php';
$stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
$stmt->execute([$userId]);
echo json_encode(['success' => true]);