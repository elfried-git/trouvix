<?php
// Fichier : backend/salons.php
// API pour gérer les salons (création, récupération, mise à jour)

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// --- CONFIG BDD ---
$DB_HOST = 'localhost';
$DB_NAME = 'trouvix'; // À adapter
$DB_USER = 'root'; // À adapter
$DB_PASS = ''; // À adapter

// --- Connexion PDO ---
try {
	$pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	]);
} catch (Exception $e) {
	// Erreur 500 si la connexion échoue
	http_response_code(500);
	echo json_encode(['error' => 'Erreur connexion BDD: ' . $e->getMessage()]);
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
		// CORRECTION ROBUSTESSE: S'assurer que 'joueurs' est toujours un tableau
		$decoded_joueurs = json_decode($salon['joueurs'], true);
		$salon['joueurs'] = is_array($decoded_joueurs) ? $decoded_joueurs : []; 
		
		// Ajout : expose toujours le nom de l'hôte principal (pour le cas où nom_hote n'est pas rempli)
		if (!isset($salon['nom_hote']) || !$salon['nom_hote']) {
			if (isset($salon['joueurs'][0]['nom'])) {
				$salon['nom_hote'] = $salon['joueurs'][0]['nom'];
			} else {
				$salon['nom_hote'] = '';
			}
		}
	}
	// Enlève la référence pour éviter les effets de bord
	unset($salon); 
	echo json_encode($salons);
	exit;
}

if ($method === 'POST') {
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nom'], $data['code'], $data['maxJoueurs'], $data['longueurMot'], $data['joueurs'])) {
	http_response_code(400);
	echo json_encode(['error' => 'Champs manquants dans la requête POST']);
	exit;
}

// Sécurité/Préparation des données
$nom = substr(strip_tags($data['nom']), 0, 50);
$code = substr(strip_tags($data['code']), 0, 10);
$maxJoueurs = (int)$data['maxJoueurs'];
$longueurMot = (int)$data['longueurMot'];
$joueursArr = $data['joueurs'];

// Validation de l'hôte (joueur[0])
$nomHote = '';
if (!is_array($joueursArr) || count($joueursArr) === 0 || !isset($joueursArr[0]['nom']) || empty($joueursArr[0]['nom'])) {
	http_response_code(400);
	echo json_encode(['error' => 'L\'hôte doit être présent et nommé en index 0.']);
	exit;
}
$nomHote = substr(strip_tags($joueursArr[0]['nom']), 0, 50);

// Assurer une photo de fallback pour l'hôte si la valeur est vide
if (empty($joueursArr[0]['photo'])) {
	$joueursArr[0]['photo'] = '../assets/avatar-default.png';
}

$joueurs = json_encode($joueursArr);
$createdAt = time();

// Vérifie si le salon existe déjà (par code)
$stmt = $pdo->prepare('SELECT id FROM salons WHERE code = ?');
$stmt->execute([$code]);
$salonExistant = $stmt->fetch(PDO::FETCH_ASSOC);

if ($salonExistant) {
	// Mise à jour du salon (quand un joueur rejoint)
	$stmt = $pdo->prepare('UPDATE salons SET joueurs = ?, nom_hote = ?, nom = ?, maxJoueurs = ?, longueurMot = ? WHERE code = ?');
	$stmt->execute([$joueurs, $nomHote, $nom, $maxJoueurs, $longueurMot, $code]);
	echo json_encode(['success' => true, 'updated' => true]);
	exit;
} else {
	// Création du salon
	$stmt = $pdo->prepare('INSERT INTO salons (nom, code, maxJoueurs, longueurMot, joueurs, created_at, nom_hote) VALUES (?, ?, ?, ?, ?, ?, ?)');
	$stmt->execute([$nom, $code, $maxJoueurs, $longueurMot, $joueurs, $createdAt, $nomHote]);
	echo json_encode(['success' => true, 'created' => true]);
	exit;
}
}
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
exit;