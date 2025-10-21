<?php
header('Content-Type: application/json');
require_once 'db.php';

$pdo->exec("UPDATE notifications SET is_read = 1 WHERE is_read = 0");
echo json_encode(['success' => true]);
