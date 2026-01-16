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
    if (isset($_SESSION['user_nom']) && isset($_SESSION['user_email'])) {
        echo json_encode([
            'nom' => $_SESSION['user_nom'],
            'email' => $_SESSION['user_email']
        ]);
    } elseif (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
        echo json_encode([
            'nom' => $_SESSION['admin_name'],
            'email' => 'admin@trouvix.local'
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Utilisateur non connecté']);
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
}
