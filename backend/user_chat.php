<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non connecté']);
    exit;
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'] ?? 'Utilisateur';

// Envoyer un message à l'admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message = $data['message'] ?? '';
    
    if (empty(trim($message))) {
        echo json_encode(['success' => false, 'error' => 'Message vide']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO chat_messages (user_id, user_name, message, is_from_admin, created_at)
            VALUES (:user_id, :user_name, :message, 0, NOW())
        ");
        
        $stmt->execute([
            ':user_id' => $userId,
            ':user_name' => $userName,
            ':message' => $message
        ]);
        
        echo json_encode([
            'success' => true,
            'message_id' => $pdo->lastInsertId(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur base de données']);
    }
    exit;
}

// Récupérer les messages de la conversation (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Récupérer les messages de cet utilisateur et les réponses admin
        $stmt = $pdo->prepare("
            SELECT id, user_id, user_name, message, is_from_admin, is_read, created_at
            FROM chat_messages
            WHERE user_id = :user_id
            ORDER BY created_at ASC
        ");
        
        $stmt->execute([':user_id' => $userId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Marquer les messages admin comme lus
        $pdo->prepare("
            UPDATE chat_messages 
            SET is_read = 1 
            WHERE user_id = :user_id AND is_from_admin = 1 AND is_read = 0
        ")->execute([':user_id' => $userId]);
        
        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur base de données']);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
