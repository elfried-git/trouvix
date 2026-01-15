<?php
session_start();
header('Content-Type: application/json');
if (isset($_SESSION['user_nom']) && isset($_SESSION['user_email'])) {
    require_once 'db.php';
    $stmt = $pdo->prepare('SELECT photo FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    $photo = ($row && !empty($row['photo'])) ? $row['photo'] : '../assets/avatar-default.png';
    echo json_encode([
        'nom' => $_SESSION['user_nom'],
        'email' => $_SESSION['user_email'],
        'photo' => $photo
    ]);
} elseif (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
    echo json_encode([
        'nom' => $_SESSION['admin_name'],
        'email' => 'admin@trouvix.local'
    ]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
}
