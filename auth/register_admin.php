<?php
// register_admin.php
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo 'Veuillez remplir tous les champs.';
        exit;
    }

    // Vérifier si l'admin existe déjà dans la table users
    $stmt = $pdo->prepare('SELECT id FROM users WHERE LOWER(email) = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo 'Un compte existe déjà avec cet email.';
        exit;
    }

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer le nouvel admin dans la table users
    $stmt = $pdo->prepare('INSERT INTO users (nom, otp, email, is_admin) VALUES (?, ?, ?, 1)');
    $nom = 'admin';
    if ($stmt->execute([$nom, $hashedPassword, $email])) {
        echo 'Compte administrateur créé avec succès. <a href="admin-login.html">Se connecter</a>';
    } else {
        echo 'Erreur lors de la création du compte.';
    }
} else {
    header('Location: register_admin.html');
    exit;
}
