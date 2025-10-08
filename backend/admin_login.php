<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, otp, is_admin FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        if ($row['is_admin'] == 1 && password_verify($password, $row['otp'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['is_admin'] = true;
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
