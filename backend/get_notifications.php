<?php
// backend/get_notifications.php
header('Content-Type: application/json');
require_once 'db.php';

// Récupérer toutes les notifications, non lues d'abord
$stmt = $pdo->query("SELECT id, host, message, is_read, created_at FROM notifications ORDER BY is_read ASC, created_at DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);