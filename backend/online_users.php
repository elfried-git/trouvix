<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}

$stmt = $pdo->prepare('SELECT nom, email, last_activity FROM users');
$stmt->execute();
$users = $stmt->fetchAll();

$now = new DateTime();
foreach ($users as &$user) {
    $user['is_online'] = false;
    if ($user['last_activity'] === null) {
        $user['is_online'] = false;
    } elseif ($user['last_activity']) {
        $last = new DateTime($user['last_activity']);
        $diff = $now->getTimestamp() - $last->getTimestamp();
        if ($diff <= 20) { 
            $user['is_online'] = true;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($users);
