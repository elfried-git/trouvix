<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// backend/register.php
require_once 'db.php';

// Vérifier que la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $ville = $_POST['ville'] ?? null;
    $otp = $_POST['otp'] ?? '';

    // Validation simple
    if (empty($nom) || empty($email) || empty($otp)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tous les champs obligatoires doivent être remplis.']);
        exit;
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Cet email existe déjà.']);
        exit;
    }

    // Insérer l'utilisateur
    $stmt = $pdo->prepare('INSERT INTO users (nom, email, ville, otp) VALUES (?, ?, ?, ?)');
    $stmt->execute([$nom, $email, $ville, $otp]);

    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}
?>
