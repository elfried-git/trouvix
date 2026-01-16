<?php
session_start();
require_once 'db.php';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW(), session_token = NULL WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}
if (isset($_SESSION['admin_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW(), session_token = NULL WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
}
// Notifie la déconnexion dans localStorage (pour JS)
if (isset($_SESSION['user_email'])) {
    echo "<script>localStorage.setItem('logout_" . addslashes($_SESSION['user_email']) . "', Date.now());</script>";
}
session_unset();
session_destroy();
header('Location: ../auth/admin-login.html');
exit();
