<?php
// Connexion à la base de données
require_once 'db.php';
header('Content-Type: application/json');

// Récupérer tous les sujets du forum
$stmt = $pdo->query('SELECT * FROM forum_topics ORDER BY id DESC');
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($topics);