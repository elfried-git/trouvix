<?php
// Connexion à la base de données
require_once 'db.php';
session_start();
// Autoriser tous les utilisateurs connectés (ayant un identifiant en session)
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Accès refusé : utilisateur non connecté']);
    exit;
}
header('Content-Type: application/json');

// Récupérer les données POST (JSON)
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Données manquantes']);
    exit;
}

$title = trim($data['title'] ?? '');
$content = trim($data['content'] ?? '');
$author = trim($data['author'] ?? 'Anonyme');
$category = trim($data['category'] ?? 'general');
$video = trim($data['video'] ?? '');
$attachment = trim($data['attachment'] ?? '');

if (!$title || !$content) {
    echo json_encode(['success' => false, 'error' => 'Titre ou contenu manquant']);
    exit;
}

// Préparer et exécuter la requête
$stmt = $pdo->prepare('INSERT INTO forum_topics (title, content, author, category, video, attachment) VALUES (?, ?, ?, ?, ?, ?)');
$ok = $stmt->execute([$title, $content, $author, $category, $video, $attachment]);

if ($ok) {
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'ajout']);
}
