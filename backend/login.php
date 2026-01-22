<?php
ini_set('display_errors', 0); 
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
}
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Nettoyage automatique : supprime les tokens vieux de plus de 30 minutes
        $pdo->exec("UPDATE users SET session_token = NULL WHERE session_token IS NOT NULL AND (last_activity IS NULL OR last_activity < NOW() - INTERVAL 30 MINUTE)");

        // Supprime le token si aucune session PHP active n'existe pour ce compte (sécurité supplémentaire)
        // (Ce code doit aussi être appelé dans un script de vérification périodique côté serveur pour une sécurité maximale)
        if (session_status() === PHP_SESSION_ACTIVE) {
            $currentUserId = $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null;
            if ($currentUserId) {
                $stmt = $pdo->prepare('UPDATE users SET session_token = NULL WHERE id = ? AND session_token IS NOT NULL');
                $stmt->execute([$currentUserId]);
            }
        }
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
        // BLOQUE toute nouvelle connexion si un token existe et que la dernière activité est récente (<30min)
        $sessionActive = false;
        if (!empty($user['session_token'])) {
            if (!empty($user['last_activity'])) {
                $last = strtotime($user['last_activity']);
                if ($last && (time() - $last) < 20) { // 20s = 20 secondes
                    $sessionActive = true;
                }
            }
        }
        if ($sessionActive) {
            http_response_code(403);
            echo json_encode(['error' => 'Ce compte est déjà connecté sur un autre appareil ou navigateur. Veuillez vous déconnecter d’abord.']);
            exit;
        }
        // Génère un token unique
        $session_token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare('UPDATE users SET last_activity = NOW(), session_token = ? WHERE id = ?');
        $stmt->execute([$session_token, $user['id']]);

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
            $_SESSION['session_token'] = $session_token;
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['username'] = $user['nom'];
            $_SESSION['session_token'] = $session_token;
        }
        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie !',
            'user_nom' => $user['nom']
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
