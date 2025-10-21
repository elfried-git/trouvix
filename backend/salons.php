<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$DB_HOST = 'localhost';
$DB_NAME = 'trouvix'; // À adapter
$DB_USER = 'root'; // À adapter
$DB_PASS = ''; // À adapter

try {
	$pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	]);
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['error' => 'Erreur connexion BDD: ' . $e->getMessage()]);
	exit;
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
	http_response_code(200);
	exit;
}

if ($method === 'GET') {
	$stmt = $pdo->query('SELECT * FROM salons ORDER BY created_at DESC');
	$salons = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($salons as &$salon) {
		$decoded_joueurs = json_decode($salon['joueurs'], true);
		$salon['joueurs'] = is_array($decoded_joueurs) ? $decoded_joueurs : []; 
		
		if (!isset($salon['nom_hote']) || !$salon['nom_hote']) {
			if (isset($salon['joueurs'][0]['nom'])) {
				$salon['nom_hote'] = $salon['joueurs'][0]['nom'];
			} else {
				$salon['nom_hote'] = '';
			}
		}
	}
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

$nom = substr(strip_tags($data['nom']), 0, 50);
$code = substr(strip_tags($data['code']), 0, 10);
$maxJoueurs = (int)$data['maxJoueurs'];
$longueurMot = (int)$data['longueurMot'];
$joueursArr = $data['joueurs'];

$nomHote = '';
if (!is_array($joueursArr) || count($joueursArr) === 0 || !isset($joueursArr[0]['nom']) || empty($joueursArr[0]['nom'])) {
	http_response_code(400);
	echo json_encode(['error' => 'L\'hôte doit être présent et nommé en index 0.']);
	exit;
}
$nomHote = substr(strip_tags($joueursArr[0]['nom']), 0, 50);

if (empty($joueursArr[0]['photo'])) {
	$joueursArr[0]['photo'] = '../assets/avatar-default.png';
}

$joueurs = json_encode($joueursArr);
$createdAt = time();

$stmt = $pdo->prepare('SELECT id FROM salons WHERE code = ?');
$stmt->execute([$code]);
$salonExistant = $stmt->fetch(PDO::FETCH_ASSOC);

if ($salonExistant) {
	session_start();
	$session_nom = isset($_SESSION['user_nom']) ? $_SESSION['user_nom'] : (isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : null);
	if (isset($_SESSION['admin_name'])) {
		http_response_code(403);
		echo json_encode(['error' => 'Un administrateur ne peut pas rejoindre un salon en tant que joueur.']);
		exit;
	}
	$hote_bdd = null;
	$stmt2 = $pdo->prepare('SELECT joueurs, nom, nom_hote FROM salons WHERE code = ?');
	$stmt2->execute([$code]);
	$row = $stmt2->fetch(PDO::FETCH_ASSOC);
	if ($row) {
		$joueurs_bdd = json_decode($row['joueurs'], true);
		if (isset($joueurs_bdd[0]['nom'])) {
			$hote_bdd = $joueurs_bdd[0]['nom'];
		}
	}
	$hote_nouveau = isset($joueursArr[0]['nom']) ? $joueursArr[0]['nom'] : null;
	$hote_modifie = ($hote_bdd !== $hote_nouveau);
	$isHote = isset($joueursArr[0]['estHote']) && $joueursArr[0]['estHote'] === true;
	if (!$isHote || !$session_nom || $session_nom !== $hote_bdd) {
		if ($row && isset($joueurs_bdd[0])) {
			$joueursArr[0] = $joueurs_bdd[0];
		}
	} else {
		if ($hote_modifie && (!$isHote || !$session_nom || $session_nom !== $hote_bdd)) {
			http_response_code(403);
			echo json_encode(['error' => 'Modification du slot hôte interdite.']);
			exit;
		}
	}
	$nom_bdd = isset($row['nom']) ? $row['nom'] : null;
	$nom_hote_bdd = isset($row['nom_hote']) ? $row['nom_hote'] : null;
	$infos_modifiees = ($nom !== $nom_bdd || $nomHote !== $nom_hote_bdd);
	if ($infos_modifiees && (!$isHote || !$session_nom || $session_nom !== $hote_bdd)) {
		http_response_code(403);
		echo json_encode(['error' => 'Seul l’hôte peut modifier les infos du salon.']);
		exit;
	}
	$stmt = $pdo->prepare('UPDATE salons SET joueurs = ? WHERE code = ?');
	$stmt->execute([$joueurs, $code]);
	echo json_encode(['success' => true, 'updated' => true]);
	exit;
} else {
	session_start();
	if (isset($_SESSION['admin_name'])) {
		http_response_code(403);
		echo json_encode(['error' => 'Un administrateur ne peut pas créer de salon.']);
		exit;
	}
	$user_nom = isset($_SESSION['user_nom']) ? $_SESSION['user_nom'] : null;
	$user_photo = (isset($_SESSION['user_photo']) && !empty($_SESSION['user_photo'])) ? $_SESSION['user_photo'] : '../assets/avatar-default.png';
	if (isset($_SESSION['admin_name'])) {
		$user_photo = '../assets/avatar-default.png';
	}
	if (!$user_nom) {
		http_response_code(401);
		echo json_encode(['error' => 'Utilisateur non connecté.']);
		exit;
	}
	$joueursArr[0] = [
		'nom' => $user_nom,
		'photo' => $user_photo,
		'estHote' => true
	];
	$joueurs = json_encode($joueursArr);
	$stmt = $pdo->prepare('INSERT INTO salons (nom, code, maxJoueurs, longueurMot, joueurs, created_at, nom_hote) VALUES (?, ?, ?, ?, ?, ?, ?)');
	$stmt->execute([$nom, $code, $maxJoueurs, $longueurMot, $joueurs, $createdAt, $user_nom]);
	echo json_encode(['success' => true, 'created' => true]);
	exit;
}
}
http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée']);
exit;