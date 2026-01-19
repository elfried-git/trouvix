<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $otp = $_POST['otp'] ?? '';

    $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);

    if (empty($nom) || empty($email) || empty($otp)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tous les champs obligatoires doivent être remplis.']);
        exit;
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE LOWER(email) = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Cet email existe déjà.']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO users (nom, email, otp) VALUES (?, ?, ?)');
    $stmt->execute([$nom, $email, $hashedOtp]);

    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}
?>
