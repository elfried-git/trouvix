<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();
require_once 'db.php';
$userId = null;
$sessionToken = $_SESSION['session_token'] ?? null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} elseif (isset($_SESSION['admin_id'])) {
    $userId = $_SESSION['admin_id'];
}
if ($userId && $sessionToken) {
    $stmt = $pdo->prepare('SELECT session_token FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row || $row['session_token'] !== $sessionToken) {
        session_unset();
        session_destroy();
        http_response_code(401);
        echo json_encode(['error' => 'Session expirée ou connectée ailleurs']);
        exit;
    }
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}
if (!isset($_SESSION['user_nom'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['code']) || !isset($data['nom'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs manquants']);
    exit;
}
$code = substr(strip_tags($data['code']), 0, 10);
$nomARetirer = substr(strip_tags($data['nom']), 0, 50);

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
$stmt = $pdo->prepare('SELECT joueurs FROM salons WHERE code = ?');
$stmt->execute([$code]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'Salon introuvable']);
    exit;
}
$joueurs = json_decode($row['joueurs'], true);
if (!is_array($joueurs) || !isset($joueurs[0]['nom']) || $_SESSION['user_nom'] !== $joueurs[0]['nom']) {
    http_response_code(403);
    echo json_encode(['error' => 'Seul l\'hôte peut retirer un joueur']);
    exit;
}
if ($nomARetirer === $joueurs[0]['nom']) {
    http_response_code(403);
    echo json_encode(['error' => 'Impossible de retirer l\'hôte']);
    exit;
}
$nouveauxJoueurs = [];
foreach ($joueurs as $j) {
    if (!isset($j['nom']) || $j['nom'] !== $nomARetirer) {
        $nouveauxJoueurs[] = $j;
    } else {
        $nouveauxJoueurs[] = [ 'nom' => '', 'estHote' => false ];
    }
}
$stmt = $pdo->prepare('UPDATE salons SET joueurs = ? WHERE code = ?');
$stmt->execute([json_encode($nouveauxJoueurs), $code]);
// Notify listeners that joueurs changed (file fallback)
$eventFilename = __DIR__ . '/../tmp/salon_event_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $code) . '.json';
@file_put_contents($eventFilename, json_encode(['event' => 'joueurs_modifies', 'code' => $code, 'removed' => $nomARetirer]));
// Try to notify WebSocket server (best-effort, non-blocking)
$wsPayload = json_encode(['event' => 'joueurs_modifies', 'code' => $code, 'removed' => $nomARetirer]);
$wsUrl = 'http://127.0.0.1:3001/notify';
$ch = curl_init($wsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $wsPayload);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 200);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 600);
curl_exec($ch);
curl_close($ch);
echo json_encode(['success' => true]);
exit;
