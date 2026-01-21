<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

// Vérifier si l'utilisateur est admin
// Accepter soit admin_id (session admin) soit user_id + is_admin (session utilisateur)
$isAdmin = false;
if (isset($_SESSION['admin_id'])) {
    $isAdmin = true;
} elseif (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    $isAdmin = true;
}

if (!$isAdmin) {
    echo json_encode(['success' => false, 'error' => 'Accès non autorisé - Session: ' . json_encode($_SESSION)]);
    exit;
}

// Récupérer tous les messages des utilisateurs (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Récupérer TOUS les messages (utilisateurs ET admin) pour afficher la conversation complète
        $stmt = $pdo->query("
            SELECT 
                cm.id, 
                cm.user_id, 
                cm.user_name, 
                cm.message, 
                cm.is_from_admin, 
                cm.is_read,
                cm.created_at
            FROM chat_messages cm
            ORDER BY cm.created_at ASC
        ");
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Compter le nombre de messages non lus
        $unreadStmt = $pdo->query("
            SELECT COUNT(*) as total 
            FROM chat_messages 
            WHERE is_from_admin = 0 AND is_read = 0
        ");
        $unreadCount = $unreadStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        echo json_encode([
            'success' => true,
            'messages' => $messages,
            'unread_count' => $unreadCount
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Erreur base de données: ' . $e->getMessage()]);
    }
    exit;
}

// Marquer un message comme lu (PUT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    
    // Marquer comme lu
    if ($action === 'mark_read') {
        $messageId = $data['message_id'] ?? 0;
        
        try {
            $stmt = $pdo->prepare("
                UPDATE chat_messages 
                SET is_read = 1 
                WHERE id = :id AND is_from_admin = 0
            ");
            
            $stmt->execute([':id' => $messageId]);
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erreur base de données']);
        }
        exit;
    }
    
    // Envoyer une réponse à un utilisateur
    if ($action === 'reply') {
        $userId = $data['user_id'] ?? 0;
        $userName = $data['user_name'] ?? 'Admin';
        $message = $data['message'] ?? '';
        
        if (empty(trim($message)) || $userId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Données invalides']);
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO chat_messages (user_id, user_name, message, is_from_admin, created_at)
                VALUES (:user_id, :user_name, :message, 1, NOW())
            ");
            
            $stmt->execute([
                ':user_id' => $userId,
                ':user_name' => 'Admin',
                ':message' => $message
            ]);
            
            echo json_encode([
                'success' => true,
                'message_id' => $pdo->lastInsertId()
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erreur base de données']);
        }
        exit;
    }
    
    // Supprimer une conversation complète avec un utilisateur
    if ($action === 'delete_conversation') {
        $userId = $data['user_id'] ?? 0;
        
        if ($userId <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID utilisateur invalide']);
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("
                DELETE FROM chat_messages 
                WHERE user_id = :user_id
            ");
            
            $stmt->execute([':user_id' => $userId]);
            
            echo json_encode([
                'success' => true,
                'deleted_count' => $stmt->rowCount()
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Erreur base de données: ' . $e->getMessage()]);
        }
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Action non reconnue']);
