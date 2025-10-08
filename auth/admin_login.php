<?php
session_start();
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, nom, otp, is_admin FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if ($row) {
        if ($row['is_admin'] == 1 && password_verify($password, $row['otp'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_name'] = !empty($row['nom']) ? $row['nom'] : 'Administrateur';
            header('Location: admin-dashboard.php');
            exit();
        } else {
            $error = 'Accès refusé ou OTP incorrect.';
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
    <link rel="stylesheet" href="auth.css">
</head>
<body>
<?php if (isset($error)) { echo '<p style="color:red;">'.$error.'</p>'; } ?>
</body>
</html>
