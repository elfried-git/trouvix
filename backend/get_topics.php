<?php
require_once 'db.php';
header('Content-Type: application/json');

$stmt = $pdo->query('SELECT * FROM forum_topics ORDER BY id DESC');
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($topics);