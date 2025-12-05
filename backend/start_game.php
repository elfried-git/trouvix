<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

session_start();
if (!isset($_SESSION['user_nom'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['code'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Code salon manquant']);
    exit;
}

$code = substr(strip_tags($data['code']), 0, 10);
$userNom = $_SESSION['user_nom'];

$DB_HOST = 'localhost';
$DB_NAME = 'trouvix';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur connexion BDD']);
    exit;
}

// Vérifier que l'utilisateur est l'hôte
$stmt = $pdo->prepare('SELECT joueurs FROM salons WHERE code = ?');
$stmt->execute([$code]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'Salon introuvable']);
    exit;
}

$joueurs = json_decode($row['joueurs'], true);
if (!isset($joueurs[0]['nom']) || $joueurs[0]['nom'] !== $userNom) {
    http_response_code(403);
    echo json_encode(['error' => 'Seul l\'hôte peut démarrer le jeu']);
    exit;
}

// Broadcast jeu_demarrage via WebSocket (best-effort)
$wsPayload = json_encode(['event' => 'jeu_demarrage', 'code' => $code]);
$wsUrl = 'http://127.0.0.1:3001/notify';
$ch = curl_init($wsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $wsPayload);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 300);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 800);
curl_exec($ch);
curl_close($ch);

// Fallback: write event file for SSE
$eventFilename = __DIR__ . '/../tmp/salon_event_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $code) . '.json';
@file_put_contents($eventFilename, json_encode(['event' => 'jeu_demarrage', 'code' => $code]));
// Write game start marker with timestamp for rapid polling
$gameStartFile = __DIR__ . '/../tmp/game_start_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $code) . '.txt';
@file_put_contents($gameStartFile, time());

echo json_encode(['success' => true, 'code' => $code]);
exit;
