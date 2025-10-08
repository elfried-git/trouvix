<?php
session_set_cookie_params(['path' => '/']);
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.html');
    exit;
}
$user_nom = $_SESSION['user_nom'];
$user_email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Membre - Trouvix</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <div class="header-row">
            <div class="logo" tabindex="0" aria-label="Accueil Trouvix">
                <span class="logo-text">Trouvix</span>
            </div>
            <button id="close-menu" class="close-menu" aria-label="Fermer le menu" style="display:none">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" stroke="#0ff1ce" stroke-width="3"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="8" x2="24" y2="24" />
                    <line x1="24" y1="8" x2="8" y2="24" />
                </svg>
            </button>
            <nav id="main-nav" class="main-nav" aria-label="Navigation principale">
                <ul>
                    <li><a href="../index.html">Accueil</a></li>
                    <li><a href="../index.html#contact">Contact</a></li>
                        <li id="menu-user-icon" style="display:none">
                            <a href="espace-membre.php" title="Espace membre" style="display:flex;align-items:center;gap:0.4em;">
                                <span style="font-size:1.5em;">👤</span>
                                <span id="menu-user-nom" style="font-size:1em;"></span>
                            </a>
                        </li>
                        <li id="menu-login-link"><a href="../auth/login.html">Connexion</a></li>
                        <li id="menu-logout-link" style="display:none"><form action="logout.php" method="post" style="display:inline;"><button type="submit" style="background:none;border:none;color:#0ff1ce;font-size:1em;cursor:pointer;">Déconnexion</button></form></li>
                </ul>
            </nav>
        </div>
    </header>
    <script src="../js/session-nav.js"></script>
    <div class="client-space-container">
        <div class="profile-card">
            <div class="profile-avatar">
                <span><?php echo strtoupper(substr($user_nom, 0, 1)); ?></span>
            </div>
            <div class="profile-info">
                <h2>Bienvenue, <span class="profile-name"><?php echo htmlspecialchars($user_nom); ?></span> !</h2>
                <div class="profile-email">Email : <span><?php echo htmlspecialchars($user_email); ?></span></div>
            </div>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
        </div>
        <div class="client-space-content">
            <h3>Votre espace membre</h3>
            <ul class="client-features">
                <li>Bientôt disponible ...</li>
                <!-- <li>Gestion de votre profil</li>
                <li>Support et assistance dédiés</li>
                <li>Actualités et nouveautés Trouvix</li> -->
            </ul>
        </div>
    </div>
</body>
</html>
<style>
.client-space-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    background: var(--bg-gradient, linear-gradient(135deg, #0a0a23 0%, #1a2236 100%));
    padding-top: 4vh;
}
.profile-card {
    background: var(--card-bg, rgba(20,30,60,0.95));
    box-shadow: var(--shadow, 0 8px 32px 0 rgba(0,255,255,0.12));
    border-radius: 1.5rem;
    padding: 2.5rem 2.5rem 2rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2.5rem;
    min-width: 320px;
    max-width: 95vw;
    position: relative;
}
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ff1ce 60%, #a259ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.7rem;
    color: #181c3a;
    font-weight: bold;
    margin-bottom: 1.2rem;
    box-shadow: 0 0 18px #0ff1ce55;
}
.profile-info {
    text-align: center;
    margin-bottom: 1.2rem;
}
.profile-name {
    color: #0ff1ce;
    font-weight: 700;
}
.profile-email {
    color: #8be9fd;
    font-size: 1.08em;
    margin-top: 0.2em;
}
.logout-form {
    margin-top: 1.2rem;
}
.logout-btn {
    background: linear-gradient(90deg, #00ffe7 60%, #a259ff 100%);
    color: #181c3a;
    border: none;
    border-radius: 0.7rem;
    padding: 0.9rem 2.2rem;
    font-size: 1.13rem;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 0 16px #00ffe766;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.logout-btn:hover, .logout-btn:focus {
    background: linear-gradient(90deg, #a259ff 60%, #00ffe7 100%);
    color: #fff;
    box-shadow: 0 0 24px #a259ff99;
}
.client-space-content {
    background: var(--card-bg, rgba(20,30,60,0.95));
    box-shadow: var(--shadow, 0 8px 32px 0 rgba(0,255,255,0.12));
    border-radius: 1.3rem;
    padding: 2.2rem 2rem 2rem 2rem;
    max-width: 420px;
    width: 100%;
    text-align: center;
}
.client-space-content h3 {
    color: #0ff1ce;
    margin-bottom: 1.2rem;
    font-size: 1.35em;
}
.client-features {
    list-style: none;
    padding: 0;
    margin: 0;
    color: #eaf6fb;
    font-size: 1.08em;
}
.client-features li {
    margin-bottom: 0.8em;
    padding-left: 1.2em;
    position: relative;
    text-align: left;
}
.client-features li:before {
    content: '\2714';
    color: #0ff1ce;
    position: absolute;
    left: 0;
    font-size: 1.1em;
    top: 0.1em;
}
@media (max-width: 600px) {
    .profile-card, .client-space-content {
        min-width: unset;
        padding: 1.2rem 0.5rem;
    }
    .client-space-content {
        max-width: 98vw;
    }
}
</style>
</html>
