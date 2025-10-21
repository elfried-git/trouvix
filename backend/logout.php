<?php
session_start();
require_once 'db.php';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NULL WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}
session_unset();
session_destroy();
header('Location: ../auth/admin-login.html');
exit();
