<?php
header('Content-Type: application/json');
require_once 'db.php'; 

$data = json_decode(file_get_contents('php://input'), true);

$code = isset($data['code']) ? trim($data['code']) : '';
$event = isset($data['event']) ? trim($data['event']) : '';
if (!$code || !$event) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
    exit;
}
$filename = __DIR__ . '/../tmp/salon_event_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $code) . '.json';
file_put_contents($filename, json_encode(['event' => $event, 'code' => $code]));
echo json_encode(['success' => true]);
