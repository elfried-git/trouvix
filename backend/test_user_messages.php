<?php
// Simuler une session utilisateur
session_start();
$_SESSION['user_id'] = 22;
$_SESSION['user_nom'] = 'USER-TEST';

echo "=== Test de récupération des messages utilisateur ===\n\n";

require_once 'db.php';

try {
    $stmt = $pdo->prepare("
        SELECT id, user_id, user_name, message, is_from_admin, is_read, created_at
        FROM chat_messages
        WHERE user_id = :user_id
        ORDER BY created_at ASC
    ");
    
    $stmt->execute([':user_id' => 22]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Nombre de messages trouvés: " . count($messages) . "\n\n";
    
    foreach ($messages as $msg) {
        $type = $msg['is_from_admin'] == 1 ? 'ADMIN' : 'UTILISATEUR';
        echo "[$type] {$msg['user_name']}: {$msg['message']}\n";
        echo "  - Date: {$msg['created_at']}\n";
        echo "  - Lu: " . ($msg['is_read'] ? 'Oui' : 'Non') . "\n\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
