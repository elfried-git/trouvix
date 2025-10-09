<?php
// backend/delete_all_notifications.php
header('Content-Type: application/json');
require_once 'db.php';
$pdo->exec("DELETE FROM notifications");
echo json_encode(['success' => true]);
