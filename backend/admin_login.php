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
            $_SESSION['user_id'] = $row['id']; 
            $stmt2 = $conn->prepare('SELECT nom, email FROM users WHERE id = ? LIMIT 1');
            $stmt2->bind_param('i', $row['id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2 && $row2 = $result2->fetch_assoc()) {
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
