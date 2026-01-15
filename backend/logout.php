<?php
session_start();
require_once 'db.php';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}
if (isset($_SESSION['admin_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
}
session_unset();
session_destroy();
header('Location: ../auth/admin-login.html');
exit();
