<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, no-store, must-revalidate');

$code = isset($_GET['code']) ? substr(strip_tags($_GET['code']), 0, 10) : null;
if (!$code) {
    http_response_code(400);
    echo json_encode(['error' => 'Code manquant', 'started' => false]);
    exit;
}

// Vérifier si le fichier de démarrage existe
$gameStartFile = __DIR__ . '/../tmp/game_start_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $code) . '.txt';

if (file_exists($gameStartFile)) {
    // Le jeu a démarré
    echo json_encode(['started' => true, 'code' => $code]);
    // Nettoyer après
    @unlink($gameStartFile);
    exit;
}

// Le jeu n'a pas démarré
echo json_encode(['started' => false, 'code' => $code]);
exit;
?>
