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

    // Gestion de l'upload de la photo
    $photoPath = '../assets/avatar-default.png';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format de photo non supporté.']);
            exit;
        }
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('photo_', true) . '.' . $ext;
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $destPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destPath)) {
            $photoPath = '../uploads/' . $fileName;
        }
    }

    // Insérer l'utilisateur avec la photo
    $stmt = $pdo->prepare('INSERT INTO users (nom, email, ville, otp, photo) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$nom, $email, $ville, $otp, $photoPath]);

    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}
?>
