<?php
// Endpoint pour forcer la reconnexion d'un utilisateur (efface le session_token)
require_once 'db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email requis.']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE users SET session_token = NULL WHERE email = ?');
    $stmt->execute([$email]);
    echo json_encode(['success' => true, 'message' => 'Session réinitialisée. Vous pouvez vous reconnecter.']);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}
?>
