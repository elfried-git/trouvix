<?php
// forum/admin_login.php
session_set_cookie_params(['path' => '/']);
session_start();
require_once __DIR__ . '/../backend/db.php';
header('Content-Type: application/json');


// Si on reçoit une requête pour vérifier la session (SSO)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['checkSession']) && $data['checkSession'] === true) {
        if (isset($_SESSION['admin_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            echo json_encode(['session_active' => true]);
        } else {
            echo json_encode(['session_active' => false]);
        }
        exit;
    }

    if (!isset($data['username'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champs manquants']);
        exit;
    }

    $username = trim($data['username']);
    $password = $data['password'];

    $stmt = $pdo->prepare('SELECT id, nom, is_admin, mot_de_passe FROM users WHERE nom = ? AND is_admin = 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['is_admin'] = true;
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => true]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Identifiants invalides']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
