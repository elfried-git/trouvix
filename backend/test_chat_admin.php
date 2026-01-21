<?php
session_start();

// Simuler une session admin
$_SESSION['admin_id'] = 1;

echo "Session admin simulée: admin_id = " . $_SESSION['admin_id'] . "\n\n";

// Inclure la connexion DB
require_once 'db.php';

// Tester la requête
try {
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
    
    echo "Nombre de messages trouvés: " . count($messages) . "\n\n";
    
    echo "Messages:\n";
    print_r($messages);
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
