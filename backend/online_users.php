<?php
// backend/online_users.php
session_start();
require_once 'db.php';

// Met à jour la dernière activité de l'utilisateur connecté
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
}

// Récupère tous les utilisateurs avec leur dernière activité
$stmt = $pdo->prepare('SELECT nom, email, last_activity FROM users');
$stmt->execute();
$users = $stmt->fetchAll();

// Marque les utilisateurs connectés (activité < 2 min)
$now = new DateTime();
foreach ($users as &$user) {
    $user['is_online'] = false;
    if ($user['last_activity'] === null) {
        $user['is_online'] = false;
    } elseif ($user['last_activity']) {
        $last = new DateTime($user['last_activity']);
        $diff = $now->getTimestamp() - $last->getTimestamp();
        if ($diff <= 20) { // 20 secondes pour plus de réactivité
            $user['is_online'] = true;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($users);
