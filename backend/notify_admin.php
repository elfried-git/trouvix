<?php
// backend/notify_admin.php
// Reçoit une demande de suppression de salon et l'enregistre en base
header('Content-Type: application/json');
require_once 'db.php'; // Connexion PDO $pdo

$data = json_decode(file_get_contents('php://input'), true);
$host = isset($data['host']) ? trim($data['host']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';
if (!$host || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit;
}

// Créer la table notifications si elle n'existe pas
$pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $pdo->prepare("INSERT INTO notifications (host, message) VALUES (?, ?)");
$ok = $stmt->execute([$host, $message]);
if ($ok) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
}
