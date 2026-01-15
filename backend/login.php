<?php
ini_set('display_errors', 0); 
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
}
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    if (empty($email) || empty($otp)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email et OTP sont requis.']);
        exit;
    }
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($otp, $user['otp'])) {
        $alreadyConnected = false;
        if (!empty($user['last_activity'])) {
            $last = strtotime($user['last_activity']);
            // Réduit le délai à 2 secondes pour permettre une reconnexion rapide
            if ($last && (time() - $last) < 2) {
                $alreadyConnected = true;
            }
        }
        if ($alreadyConnected) {
            http_response_code(403);
            echo json_encode(['error' => 'Ce compte est déjà connecté sur un autre appareil ou navigateur. Veuillez vous déconnecter d’abord.']);
            exit;
        }
        $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
        $stmt->execute([$user['id']]);

        session_set_cookie_params([
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
        // Si l'utilisateur est admin, ne pas écraser les variables utilisateur
        if (isset($user['is_admin']) && $user['is_admin'] == 1) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = !empty($user['nom']) ? $user['nom'] : 'Administrateur';
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_photo'] = isset($user['photo']) && $user['photo'] ? $user['photo'] : '../assets/avatar-default.png';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['username'] = $user['nom'];
            $_SESSION['user_photo'] = isset($user['photo']) && $user['photo'] ? $user['photo'] : '../assets/avatar-default.png';
        }
        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie !',
            'user_nom' => $user['nom'],
            'user_photo' => isset($user['photo']) && $user['photo'] ? $user['photo'] : '../assets/avatar-default.png'
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Identifiants invalides.']);
    }
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}
?>
