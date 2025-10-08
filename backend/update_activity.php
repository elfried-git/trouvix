<?php
// backend/update_activity.php
session_start();
require_once 'db.php';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}
// Réponse vide, juste pour le ping
http_response_code(204);
