<?php
session_start();
if (isset($_SESSION['user_id'])) {
	require_once __DIR__ . '/backend/db.php';
	$stmt = $pdo->prepare('UPDATE users SET last_activity = NULL WHERE id = ?');
	$stmt->execute([$_SESSION['user_id']]);
}
session_unset();
session_destroy();
header('Location: admin-login.html');
exit();
?>
