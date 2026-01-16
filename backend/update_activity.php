<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';
$userId = null;
$sessionToken = $_SESSION['session_token'] ?? null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} elseif (isset($_SESSION['admin_id'])) {
    $userId = $_SESSION['admin_id'];
}
if ($userId && $sessionToken) {
    $stmt = $pdo->prepare('SELECT session_token FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row || $row['session_token'] !== $sessionToken) {
        session_unset();
        session_destroy();
        http_response_code(401);
        echo json_encode(['error' => 'Session expirée ou connectée ailleurs']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
    $stmt->execute([$userId]);
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Non connecté']);
    exit;
}