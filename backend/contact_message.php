<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$prenom = isset($data['firstName']) ? trim($data['firstName']) : '';
$nom = isset($data['lastName']) ? trim($data['lastName']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$telephone = isset($data['phone']) ? trim($data['phone']) : '';
$sujet = isset($data['subject']) ? trim($data['subject']) : '';
$num_commande = isset($data['orderNumber']) ? trim($data['orderNumber']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

if (!$prenom || !$nom || !$email || !$sujet || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Champs obligatoires manquants']);
    exit;
}
$msg = "[Contact] $prenom $nom ($email)\n";
if ($telephone) $msg .= "Téléphone: $telephone\n";
if ($num_commande) $msg .= "Commande: $num_commande\n";
$msg .= "Sujet: $sujet\nMessage: $message";
$stmt = $pdo->prepare('INSERT INTO notifications (host, message, is_read, created_at) VALUES (?, ?, 0, NOW())');
$stmt->execute(["Contact", $msg]);

echo json_encode(['success' => true, 'message' => 'Votre message a été transmis à l\'administrateur.']);
