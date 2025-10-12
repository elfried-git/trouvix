<?php
// backend/get_notifications.php
header('Content-Type: application/json');
require_once 'db.php';

if (isset($_GET['id'])) {
	$id = intval($_GET['id']);
	$stmt = $pdo->prepare("SELECT id, host, message, is_read, created_at FROM notifications WHERE id = ?");
	$stmt->execute([$id]);
	$notif = $stmt->fetch(PDO::FETCH_ASSOC);
	echo json_encode(['notification' => $notif]);
	exit;
}
// Sinon, toutes les notifications
$stmt = $pdo->query("SELECT id, host, message, is_read, created_at FROM notifications ORDER BY is_read ASC, created_at DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);