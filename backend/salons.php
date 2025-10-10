<?php
// backend/salons.php
// API pour gérer les salons (création, récupération)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// --- CONFIG ---
$DB_HOST = 'localhost';
$DB_NAME = 'trouvix'; // À adapter
$DB_USER = 'root';   // À adapter
$DB_PASS = '';       // À adapter

// --- Connexion PDO ---
try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur connexion BDD']);
    exit;
}

// --- ROUTAGE ---
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($method === 'GET') {
    // Récupérer tous les salons
    $stmt = $pdo->query('SELECT * FROM salons ORDER BY created_at DESC');
    $salons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($salons as &$salon) {
        $salon['joueurs'] = json_decode($salon['joueurs'], true);
        // Ajout : expose toujours le nom de l'hôte principal
        if (!isset($salon['nom_hote']) || !$salon['nom_hote']) {
            // fallback depuis joueurs
            if (is_array($salon['joueurs']) && isset($salon['joueurs'][0]['nom'])) {
                $salon['nom_hote'] = $salon['joueurs'][0]['nom'];
            } else {
                $salon['nom_hote'] = '';
            }
        }
    }
    echo json_encode($salons);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['nom'], $data['code'], $data['maxJoueurs'], $data['longueurMot'], $data['joueurs'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champs manquants']);
        exit;
    }
    // Sécurité : validation basique
    $nom = substr(strip_tags($data['nom']), 0, 50);
    $code = substr(strip_tags($data['code']), 0, 10);
    $maxJoueurs = (int)$data['maxJoueurs'];
    $longueurMot = (int)$data['longueurMot'];
    $joueursArr = $data['joueurs'];
    // Correction : toujours vérifier la photo du joueur
    if (is_array($joueursArr) && isset($joueursArr[0])) {
        $photo = $joueursArr[0]['photo'] ?? '';
        if (!$photo || (!str_starts_with($photo, 'data:image') && !str_starts_with($photo, 'http'))) {
            $joueursArr[0]['photo'] = '../assets/avatar-default.png';
        }
    }
    $joueurs = json_encode($joueursArr);
    $createdAt = time();
    // Ajout du nom de l'hôte principal pour affichage fiable
    $nomHote = '';
    if (is_array($joueursArr) && isset($joueursArr[0]['nom'])) {
        $nomHote = substr(strip_tags($joueursArr[0]['nom']), 0, 50);
    }
    $stmt = $pdo->prepare('INSERT INTO salons (nom, code, maxJoueurs, longueurMot, joueurs, created_at, nom_hote) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$nom, $code, $maxJoueurs, $longueurMot, $joueurs, $createdAt, $nomHote]);
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
exit;
