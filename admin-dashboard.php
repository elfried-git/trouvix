<?php
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin-login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Administrateur</title>
    <link rel="stylesheet" href="auth/auth.css">
</head>
<body>
    <div class="admin-dashboard">
        <?php
        $adminName = null;
        $adminError = null;
        if (!empty($_SESSION['admin_id'])) {
            try {
                require_once __DIR__ . '/backend/db.php';
                $stmt = $pdo->prepare('SELECT id, COALESCE(nom, "") as nom FROM users WHERE id = ? AND is_admin = 1');
                $stmt->execute([$_SESSION['admin_id']]);
                $row = $stmt->fetch();
                if ($row) {
                    $adminName = trim($row['nom']) !== '' ? $row['nom'] : '(nom vide)';
                    echo '<div style="color:#0ff;background:#222;padding:4px 8px;margin-bottom:8px;">Debug: id=' . htmlspecialchars($row['id']) . ' nom=' . htmlspecialchars($row['nom']) . '</div>';
                } else {
                    $adminError = "Aucun administrateur trouvé pour cet identifiant (id=" . htmlspecialchars($_SESSION['admin_id']) . ")";
                }
            } catch (Exception $e) {
                $adminError = "Erreur lors de la récupération du nom administrateur : " . $e->getMessage();
            }
        } else {
            $adminError = "Session administrateur invalide.";
        }
        ?>
        <?php if ($adminName): ?>
            <h1>Bienvenue, <?php echo htmlspecialchars($adminName); ?></h1>
        <?php else: ?>
            <h1 style="color:red;">Erreur : <?php echo $adminError ?: 'Administrateur inconnu'; ?></h1>
        <?php endif; ?>
        <p>Vous avez tous les droits sur la plateforme.</p>
        <ul>
            <li><span style="color:#888;cursor:not-allowed;">Gérer les utilisateurs</span></li>
            <li><span style="color:#888;cursor:not-allowed;">Gérer les contenus</span></li>
            <li><span style="color:#888;cursor:not-allowed;">Voir les statistiques</span></li>
            <li><a href="http://localhost/Trouvix/forum/index.php" target="_blank" rel="noopener">Forum</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </div>
</body>
</html>
