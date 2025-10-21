<?php

$DB_HOST = 'localhost';
$DB_NAME = 'trouvix'; // À adapter
$DB_USER = 'root';   // À adapter
$DB_PASS = '';       // À adapter

$jsonFile = __DIR__ . '/salons_local.json';
if (!file_exists($jsonFile)) {
    exit('Fichier salons_local.json introuvable. Exportez d\'abord vos salons du localStorage.');
}

$salons = json_decode(file_get_contents($jsonFile), true);
if (!is_array($salons)) {
    exit('Format JSON invalide.');
}

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    exit('Erreur connexion BDD: ' . $e->getMessage());
}

$inserted = 0;
foreach ($salons as $salon) {
    $nom = isset($salon['nom']) ? substr(strip_tags($salon['nom']), 0, 50) : '';
    $code = isset($salon['code']) ? substr(strip_tags($salon['code']), 0, 10) : '';
    $maxJoueurs = isset($salon['maxJoueurs']) ? (int)$salon['maxJoueurs'] : 0;
    $longueurMot = isset($salon['longueurMot']) ? (int)$salon['longueurMot'] : 0;
    $joueurs = isset($salon['joueurs']) ? json_encode($salon['joueurs']) : '[]';
    $createdAt = isset($salon['createdAt']) ? (int)$salon['createdAt'] : time();
    if ($nom && $code && $maxJoueurs && $longueurMot) {
        $stmt = $pdo->prepare('INSERT INTO salons (nom, code, maxJoueurs, longueurMot, joueurs, created_at) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nom, $code, $maxJoueurs, $longueurMot, $joueurs, $createdAt]);
        $inserted++;
    }
}
echo "Migration terminée. $inserted salons importés.";
