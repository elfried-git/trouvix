<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, otp, is_admin FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if ($row) {
        if ($row['is_admin'] == 1 && password_verify($password, $row['otp'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['is_admin'] = true;
            $stmt2 = $pdo->prepare('SELECT nom, email FROM users WHERE id = ? LIMIT 1');
            $stmt2->execute([$row['id']]);
            $row2 = $stmt2->fetch();
            if ($row2) {
                $_SESSION['user_nom'] = $row2['nom'];
                $_SESSION['user_email'] = $row2['email'];
            }
            header('Location: ../admin-dashboard.php');
            exit();
        } else {
            $error = 'Accès refusé ou mot de passe incorrect.';
        }
    } else {
        $error = 'Utilisateur non trouvé.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
</head>
<body>
<?php if (isset($error)) { echo '<p style="color:red;">'.$error.'</p>'; } ?>
</body>
</html>
