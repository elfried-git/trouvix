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
<div class="login-container" style="display:flex;justify-content:center;align-items:center;min-height:100vh;background:linear-gradient(135deg,#0a0a23 0%,#1a2236 100%);">
    <form method="post" style="background:#181c3a;padding:2.5em 2em 2em 2em;border-radius:1.2em;box-shadow:0 0 32px #00fff966,0 0 0 2px #00fff933;min-width:320px;">
        <h2 style="color:#0ff1ce;text-align:center;margin-bottom:1.5em;">Connexion Admin</h2>
        <?php if (isset($error)) { echo '<p style="color:red;text-align:center;">'.$error.'</p>'; } ?>
        <div style="margin-bottom:1.2em;">
            <label for="email" style="color:#eaf6fb;">Email</label><br>
            <input type="email" name="email" id="email" required style="width:100%;padding:0.7em 1em;font-size:1.1em;border-radius:0.4em;border:2px solid #0ff1ce;outline:none;box-shadow:0 0 8px #0ff1ce33;background:#10132a;color:#0ff1ce;margin-top:0.3em;">
        </div>
        <div style="margin-bottom:1.5em;">
            <label for="password" style="color:#eaf6fb;">Mot de passe (OTP)</label><br>
            <input type="password" name="password" id="password" required style="width:100%;padding:0.7em 1em;font-size:1.1em;border-radius:0.4em;border:2px solid #0ff1ce;outline:none;box-shadow:0 0 8px #0ff1ce33;background:#10132a;color:#0ff1ce;margin-top:0.3em;">
        </div>
        <button type="submit" style="width:100%;background:#0ff1ce;color:#181c3a;font-weight:bold;font-size:1.1em;padding:0.7em 0;border:none;border-radius:0.4em;cursor:pointer;">Se connecter</button>
    </form>
</div>
</body>
</html>
