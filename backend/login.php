<?php
ini_set('display_errors', 0); // Désactive l'affichage des erreurs dans la réponse AJAX
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
}

// backend/login.php
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
        // Vérifie si le compte est déjà connecté (last_activity < 2min)
        $alreadyConnected = false;
        if (!empty($user['last_activity'])) {
            $last = strtotime($user['last_activity']);
            if ($last && (time() - $last) < 30) { // 30 secondes
                $alreadyConnected = true;
            }
        }
        if ($alreadyConnected) {
            http_response_code(403);
            echo json_encode(['error' => 'Ce compte est déjà connecté sur un autre appareil ou navigateur. Veuillez vous déconnecter d’abord.']);
            exit;
        }
        // Met à jour la dernière activité
        $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW() WHERE id = ?');
        $stmt->execute([$user['id']]);

        session_set_cookie_params([
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['username'] = $user['nom'];
        $_SESSION['user_photo'] = isset($user['photo']) && $user['photo'] ? $user['photo'] : '../assets/avatar-default.png';
        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie !',
            'user_nom' => $user['nom'],
            'user_photo' => $_SESSION['user_photo']
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
