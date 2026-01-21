<?php
session_set_cookie_params(['path' => '/']);
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header('Location: ../auth/login.html');
    exit;
}
if (isset($_SESSION['admin_id'])) {
    header('Location: ../auth/admin-dashboard.php');
    exit;
}
$user_nom = $_SESSION['user_nom'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Membre - Trouvix</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background: #181c3a !important;
            margin: 0;
            padding: 0;
        }
        
        /* Navigation styling */
        .main-nav ul li a, .main-nav ul li span {
            color: #fff !important;
        }
        header {
            position: relative;
            z-index: 1000;
        }
        .main-nav ul li a:hover, .main-nav ul li a:focus {
            color: #181c3a !important;
            background: linear-gradient(90deg, #00fff9 0%, #ff00ff 100%);
            border-radius: 0.5em;
            padding: 0.5em 1em;
        }

        /* Main Container */
        .member-container {
            min-height: 100vh;
            padding: 3em 1.5em 2em 1.5em;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2.5em;
        }

        /* Hero Section */
        .member-hero {
            text-align: center;
            margin-top: 2em;
            margin-bottom: 2em;
        }
        .member-hero h1 {
            font-size: 2.8em;
            color: #fff;
            margin-bottom: 0.5em;
            font-weight: bold;
            text-shadow: 0 0 20px rgba(0, 255, 249, 0.5);
        }
        .member-hero .welcome-text {
            font-size: 1.3em;
            color: #0ff1ce;
            font-weight: 600;
        }

        /* Cards Grid */
        .member-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2em;
            max-width: 1400px;
            width: 100%;
        }

        /* Profile Card */
        .profile-card {
            background: rgba(24,28,58,0.98);
            border-radius: 1.5em;
            padding: 2em 1.5em;
            box-shadow: 0 0 32px #00fff933, 0 0 0 2px #00fff933;
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 48px #00fff966, 0 0 0 2px #00fff933;
        }
        .profile-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #00fff9 0%, #ff00ff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5em;
            color: #181c3a;
            margin: 0 auto 1.2em auto;
            box-shadow: 0 0 24px #00fff9cc, 0 0 48px #ff00ff66;
        }
        .profile-name {
            font-size: 1.3em;
            color: #00fff9;
            font-weight: bold;
            margin-bottom: 0.4em;
        }
        .profile-email {
            font-size: 1em;
            color: #8be9fd;
            margin-bottom: 1em;
        }

        /* Actions Card */
        .actions-card {
            background: rgba(24,28,58,0.98);
            border-radius: 1.5em;
            padding: 2em 1.5em;
            box-shadow: 0 0 32px #00fff933, 0 0 0 2px #00fff933;
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .actions-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 48px #00fff966, 0 0 0 2px #00fff933;
        }
        .actions-card h3 {
            font-size: 1.4em;
            color: #ff00ff;
            margin-bottom: 1.2em;
            font-weight: bold;
        }
        .actions-buttons {
            display: flex;
            flex-direction: row;
            gap: 1.2em;
            justify-content: center;
            align-items: stretch;
        }
        .btn-action {
            background: rgba(24, 28, 58, 0.6);
            backdrop-filter: blur(10px);
            color: #00fff9;
            font-weight: bold;
            border: 2px solid #00fff9;
            border-radius: 1.2em;
            font-size: 0.95em;
            padding: 0.8em 1.2em;
            box-shadow: 0 0 20px rgba(0, 255, 249, 0.4), inset 0 0 20px rgba(0, 255, 249, 0.1);
            letter-spacing: 0.02em;
            text-shadow: 0 0 10px rgba(0, 255, 249, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            outline: none;
            position: relative;
            overflow: hidden;
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .btn-action::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(0, 255, 249, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .btn-action:hover::before {
            width: 300px;
            height: 300px;
        }
        .btn-action:hover, .btn-action:focus {
            border-color: #ff00ff;
            color: #ff00ff;
            box-shadow: 0 0 30px rgba(255, 0, 255, 0.6), 0 0 60px rgba(0, 255, 249, 0.4), inset 0 0 30px rgba(255, 0, 255, 0.2);
            text-shadow: 0 0 15px rgba(255, 0, 255, 0.9);
            transform: translateY(-2px);
        }
        .btn-logout {
            background: rgba(34, 34, 34, 0.6);
            backdrop-filter: blur(10px);
            border: 2px solid #ff0055;
            color: #ff0055;
            box-shadow: 0 0 20px rgba(255, 0, 85, 0.4), inset 0 0 20px rgba(255, 0, 85, 0.1);
            text-shadow: 0 0 10px rgba(255, 0, 85, 0.8);
            font-weight: bold;
            border-radius: 1.2em;
            font-size: 1em;
            padding: 0.8em 1.5em;
            letter-spacing: 0.02em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            outline: none;
            position: relative;
            overflow: hidden;
        }
        .btn-logout::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 0, 85, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .btn-logout:hover::before {
            width: 300px;
            height: 300px;
        }
        .btn-logout:hover {
            border-color: #ffe600;
            color: #ffe600;
            box-shadow: 0 0 30px rgba(255, 230, 0, 0.6), 0 0 60px rgba(255, 0, 85, 0.4), inset 0 0 30px rgba(255, 230, 0, 0.2);
            text-shadow: 0 0 15px rgba(255, 230, 0, 0.9);
            transform: translateY(-2px);
        }

        /* Salon Block */
        #member-salon-block {
            background: rgba(24, 28, 58, 0.98);
            border-radius: 1.5em;
            padding: 2.5em 2em;
            width: 100%;
            max-width: 100%;
            box-shadow: 0 0 32px #00fff933, 0 0 0 2px #00fff933;
            text-align: center;
            grid-column: 1 / -1;
        }
        #member-salon-title {
            color: #00fff9;
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 0.7em;
            text-align: center;
        }
        #member-salon-code {
            color: #ffe600;
            font-size: 1.25em;
            margin-bottom: 2em;
            text-align: center;
            font-weight: 600;
            letter-spacing: 0.08em;
        }
        #member-salon-content {
            display: flex;
            flex-direction: column;
            gap: 1.8em;
        }
        #member-host-section, #member-players-section {
            background: rgba(0,255,249,0.06);
            border-radius: 1.2em;
            padding: 1.8em 1.5em;
            box-shadow: 0 0 16px #00fff922, 0 0 0 1.5px #00fff922;
        }
        #member-host-section h4, #member-players-section h4 {
            color: #ff00ff;
            font-size: 1.3em;
            margin-bottom: 1.2em;
            text-align: center;
            font-weight: bold;
        }
        #member-host-info, #member-players-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5em;
        }
        .player-block, .player-slot {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.7em;
        }
        .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 24px #00fff966, 0 0 48px #ff00ff66;
            transition: box-shadow 0.18s, transform 0.18s;
        }
        .avatar:hover {
            box-shadow: 0 0 48px #ff00ffcc, 0 0 80px #00fff9cc;
            transform: scale(1.08) rotate(-6deg);
        }
        .avatar.host {
            box-shadow: 0 0 32px #ffe600cc, 0 0 64px #00fff9cc;
        }
        .avatar.player {
            box-shadow: 0 0 24px #00fff9cc, 0 0 48px #ff00ff66;
        }
        .player-name {
            color: #00fff9;
            font-size: 1.13em;
            font-weight: 600;
        }
        .slot-status {
            color: #0ff1ce;
            font-size: 1.08em;
            font-weight: 500;
            opacity: 0.7;
        }
        #member-salon-message {
            font-size: 1.25em;
            font-weight: bold;
            margin-top: 2em;
            text-align: center;
            letter-spacing: 0.04em;
        }
        .btn-main {
            margin: 2em auto 0 auto;
            display: block;
            background: linear-gradient(90deg, #00fff9 0%, #ff00ff 100%);
            color: #181c3a;
            font-weight: bold;
            border: none;
            border-radius: 0.9em;
            font-size: 1.18em;
            padding: 0.95em 2.4em;
            box-shadow: 0 0 24px #00fff9cc, 0 0 48px #ff00ff66;
            letter-spacing: 0.04em;
            text-shadow: 0 0 8px #fff, 0 0 16px #00fff9cc;
            transition: background 0.22s, color 0.22s, box-shadow 0.22s, transform 0.13s;
            cursor: pointer;
            outline: none;
        }
        .btn-main:hover, .btn-main:focus {
            background: linear-gradient(90deg, #ff00ff 0%, #00fff9 100%);
            color: #fff;
            box-shadow: 0 0 40px #ff00ffaa, 0 0 0 2px #00fff933;
            transform: scale(1.06);
        }

        /* Modal Styles */
        .modal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(10,16,40,0.92);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-box {
            background: #181c3a;
            padding: 2.2em 2.5em 2em 2.5em;
            border-radius: 1.2em;
            box-shadow: 0 0 32px #00fff966, 0 0 0 2px #00fff933, 0 0 80px 8px #ff00ff22;
            text-align: center;
            max-width: 90vw;
            border: 1.5px solid #00fff9;
            animation: fadeIn 0.7s cubic-bezier(.4,0,.2,1);
        }
        .form-group {
            margin-bottom: 1.7em;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 0.45em;
        }
        .form-input {
            width: 85%;
            min-width: 160px;
            max-width: 260px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            background: rgba(24,28,58,0.92);
            color: #00fff9;
            border: 2px solid #00fff9;
            border-radius: 0.7em;
            padding: 0.85em 1.1em;
            font-size: 1.13em;
            outline: none;
            transition: border 0.22s, box-shadow 0.22s;
            box-shadow: 0 0 0 0 #00fff9;
            font-weight: 500;
        }
        .form-input:focus {
            border: 2.5px solid #ff00ff;
            box-shadow: 0 0 16px #ff00ff66, 0 0 24px #00fff9aa;
            background: #181c3a;
            color: #fff;
        }
        .btn-join-salon {
            background: rgba(24, 28, 58, 0.6);
            backdrop-filter: blur(10px);
            color: #00fff9;
            font-weight: bold;
            border: 2px solid #00fff9;
            border-radius: 1.2em;
            font-size: 1em;
            padding: 0.8em 1.5em;
            box-shadow: 0 0 20px rgba(0, 255, 249, 0.4), inset 0 0 20px rgba(0, 255, 249, 0.1);
            letter-spacing: 0.02em;
            text-shadow: 0 0 10px rgba(0, 255, 249, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            outline: none;
            position: relative;
            overflow: hidden;
        }
        .btn-join-salon::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(0, 255, 249, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .btn-join-salon:hover::before {
            width: 300px;
            height: 300px;
        }
        .btn-join-salon:hover {
            border-color: #ff00ff;
            color: #ff00ff;
            box-shadow: 0 0 30px rgba(255, 0, 255, 0.6), 0 0 60px rgba(0, 255, 249, 0.4), inset 0 0 30px rgba(255, 0, 255, 0.2);
            text-shadow: 0 0 15px rgba(255, 0, 255, 0.9);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .member-cards-grid {
                grid-template-columns: 1fr;
                gap: 2em;
            }
            .member-hero h1 {
                font-size: 2.2em;
            }
            .member-container {
                padding: 2em 1em;
            }
        }

        @media (max-width: 600px) {
            .profile-card, .actions-card, .chat-card {
                padding: 2em 1.5em;
            }
            .actions-buttons {
                flex-direction: column;
                align-items: center;
                gap: 1em;
            }
            .btn-action {
                width: 100%;
                max-width: 250px;
            }
            .btn-logout {
                width: 100%;
                max-width: 250px;
            }
            .chat-messages {
                max-height: 250px !important;
            }
        }

        /* Chat Card */
        .chat-card {
            background: rgba(24,28,58,0.98);
            border-radius: 1.5em;
            padding: 2em 1.5em;
            box-shadow: 0 0 32px #00fff933, 0 0 0 2px #00fff933;
            text-align: center;
            transition: transform 0.18s, box-shadow 0.18s;
            display: flex;
            flex-direction: column;
        }
        .chat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 48px #00fff966, 0 0 0 2px #00fff933;
        }
        .chat-card h3 {
            font-size: 1.4em;
            color: #ffe600;
            margin-bottom: 1.2em;
            font-weight: bold;
        }
        .chat-messages {
            flex: 1;
            background: rgba(0,0,0,0.3);
            border-radius: 1em;
            padding: 1em;
            margin-bottom: 1em;
            max-height: 300px;
            overflow-y: auto;
            text-align: left;
            border: 2px solid rgba(0,255,249,0.2);
        }
        .chat-message {
            margin-bottom: 0.8em;
            padding: 0.6em 0.8em;
            border-radius: 0.7em;
            font-size: 0.95em;
        }
        .chat-message.admin {
            background: rgba(255,230,0,0.15);
            border-left: 3px solid #ffe600;
            color: #ffe600;
        }
        .chat-message.user {
            background: rgba(0,255,249,0.15);
            border-left: 3px solid #00fff9;
            color: #00fff9;
        }
        .chat-message-author {
            font-weight: bold;
            margin-bottom: 0.3em;
            font-size: 0.9em;
        }
        .chat-input-container {
            display: flex;
            flex-direction: column;
            gap: 0.5em;
            align-items: stretch;
        }
        .chat-input {
            flex: 1;
            background: rgba(24,28,58,0.8);
            color: #00fff9;
            border: 2px solid #00fff9;
            border-radius: 0.8em;
            padding: 0.7em 1em;
            font-size: 0.95em;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
            margin-bottom: 0.5em;
        }
        .chat-input:focus {
            border-color: #ff00ff;
            box-shadow: 0 0 16px rgba(255,0,255,0.4);
        }
        .chat-send-btn {
            background: linear-gradient(90deg, #00fff9 0%, #a259ff 100%);
            color: #181c3a;
            font-weight: bold;
            border: none;
            border-radius: 0.8em;
            padding: 0.7em 1.5em;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-size: 0.95em;
            align-self: flex-end;
        }
        .chat-send-btn:hover {
            background: linear-gradient(90deg, #00e6e0 0%, #914deb 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        #scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: auto;
            height: auto;
            background: transparent;
            color: #00fff9;
            border: none;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            text-shadow: 0 2px 8px rgba(0, 255, 249, 0.6);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }
        #scroll-to-top.show {
            opacity: 0.7;
            visibility: visible;
        }
        #scroll-to-top:hover {
            opacity: 1;
            color: #ff00ff;
            transform: translateY(-3px) scale(1.4);
            text-shadow: 0 4px 16px rgba(0, 255, 249, 0.8), 0 0 20px rgba(255, 0, 255, 0.6);
        }
        #scroll-to-top:active {
            transform: translateY(-1px);
        }
        @media (max-width: 768px) {
            #scroll-to-top {
                bottom: 15px;
                right: 15px;
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <button id="scroll-to-top" aria-label="Retour en haut" title="Retour en haut" data-cy="scroll-to-top">
        ↑
    </button>
    <header data-cy="membre-header">
        <div class="header-row" id="global-header">
            <div class="logo" tabindex="0" aria-label="Accueil Trouvix" data-cy="membre-logo">
                <span class="logo-text">Trouvix</span>
            </div>
            <nav id="main-nav" class="main-nav" aria-label="Navigation principale" data-cy="membre-nav">
                <ul>
                    <li><a href="../index.html" data-cy="nav-accueil">Accueil</a></li>
                    <li><a href="../index.html#contact" data-cy="nav-contact">Contact</a></li>
                    <li id="menu-user-icon" style="display:flex;align-items:center;gap:0.4em;">
                        <span style="color:#00fff9;">👤</span>
                        <span id="menu-user-nom" style="color:#00fff9;font-weight:600;" data-cy="nav-user-name"><?php echo htmlspecialchars($user_nom); ?></span>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="member-container">
        <div class="member-hero">
            <h1 data-cy="membre-title">Bienvenue dans votre espace !</h1>
            <p class="welcome-text" data-cy="membre-welcome"><?php echo htmlspecialchars($user_nom); ?></p>
        </div>

        <div class="member-cards-grid">
            <div class="profile-card" data-cy="membre-profile-card">
                <div class="profile-icon">👤</div>
                <div class="profile-name" data-cy="membre-profile-name"><?php echo htmlspecialchars($user_nom); ?></div>
                <div class="profile-email" data-cy="membre-profile-email"><?php echo htmlspecialchars($user_email); ?></div>
            </div>

            <div class="actions-card" data-cy="membre-actions-card">
                <h3>Actions Disponibles</h3>
                <div class="actions-buttons">
                    <button class="btn-action" id="btn-join-salon" data-cy="membre-btn-join">
                        Go salon
                    </button>
                    <form action="logout.php" method="POST" style="margin: 0;">
                        <button type="submit" class="btn-action btn-logout" data-cy="membre-btn-logout">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>

            <div class="chat-card" data-cy="membre-chat-card">
                <h3>Chat avec l'admin</h3>
                <div class="chat-messages" id="chat-messages" data-cy="chat-messages">
                    <div class="chat-message admin">
                        <div class="chat-message-author">benchou ferrari</div>
                    </div>
                </div>
                <div class="chat-input-container">
                    <input type="text" class="chat-input" id="chat-input" placeholder="Vos suggestions..." data-cy="chat-input">
                    <button class="chat-send-btn" id="chat-send-btn" data-cy="chat-send-btn">Envoyer</button>
                </div>
            </div>

            <div id="member-salon-block" style="display:none;" data-cy="membre-salon-block">
                <h3 id="member-salon-title">Votre Salon</h3>
                <div id="member-salon-code"></div>
                <div id="member-salon-content">
                    <div id="member-host-section">
                        <h4>Hôte</h4>
                        <div id="member-host-info"></div>
                    </div>
                    <div id="member-players-section">
                        <h4>Joueurs</h4>
                        <div id="member-players-list"></div>
                    </div>
                </div>
                <div id="member-salon-message"></div>
                <button class="btn-main" id="btn-betou-kouenda" style="display:none;" data-cy="membre-btn-betou">Betou Kouenda</button>
            </div>
        </div>
    </div>

    <div id="modal-join-salon" style="display:none;">
        <div class="modal-bg">
            <div class="modal-box">
                <h3 style="color:#00fff9;font-size:1.4em;margin-bottom:1.5em;">Rejoindre un Salon</h3>
                <form id="form-join-salon">
                    <div class="form-group">
                        <label for="code-salon" style="color:#0ff1ce;font-size:1.1em;text-align:center;">Code du Salon</label>
                        <input type="text" id="code-salon" class="form-input" placeholder="Ex: ABC123" required data-cy="membre-input-code">
                    </div>
                    <div style="display:flex !important;flex-direction:row !important;gap:0.8em;justify-content:center;align-items:stretch !important;margin-top:2em;">
                        <button type="button" id="btn-cancel-join" class="btn-logout" style="flex:0 0 110px !important;height:40px !important;font-size:0.9em !important;padding:0 !important;border:2px solid #ff0055 !important;margin:0 !important;" data-cy="membre-btn-cancel">Annuler</button>
                        <button type="submit" class="btn-join-salon" style="flex:0 0 110px !important;height:40px !important;font-size:0.9em !important;padding:0 !important;border:2px solid #00fff9 !important;margin:0 !important;" data-cy="membre-btn-submit-join">Rejoindre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/session-nav.js"></script>
    <script>
        const btnJoinSalon = document.getElementById('btn-join-salon');
        const modalJoinSalon = document.getElementById('modal-join-salon');
        const btnCancelJoin = document.getElementById('btn-cancel-join');
        const formJoinSalon = document.getElementById('form-join-salon');

        btnJoinSalon.addEventListener('click', () => {
            modalJoinSalon.style.display = 'block';
        });

        btnCancelJoin.addEventListener('click', () => {
            modalJoinSalon.style.display = 'none';
        });

        formJoinSalon.addEventListener('submit', async (e) => {
            e.preventDefault();
            const code = document.getElementById('code-salon').value.trim();
            if (!code) return;

            window.location.href = `salon.html?code=${encodeURIComponent(code)}`;
        });

        async function fetchSalonForUser() {
            try {
                const userInfoRes = await fetch('../backend/get_user_info.php');
                if (!userInfoRes.ok) return null;
                const userInfo = await userInfoRes.json();
                if (!userInfo || !userInfo.nom) return null;
                const salonsRes = await fetch('../backend/salons.php');
                if (!salonsRes.ok) return null;
                const salons = await salonsRes.json();
                for (const salon of salons) {
                    if (Array.isArray(salon.joueurs) && salon.joueurs.some(j => j && j.nom === userInfo.nom)) {
                        return { salon, userNom: userInfo.nom };
                    }
                }
                return null;
            } catch (e) { 
                return null; 
            }
        }

        async function renderMemberSalonBlock() {
            const block = document.getElementById('member-salon-block');
            if (!block) return;
            
            const data = await fetchSalonForUser();
            if (!data) {
                block.style.display = 'none';
                return;
            }
            
            const { salon, userNom } = data;
            
            if (!salon.joueurs.some(j => j && j.nom === userNom)) {
                block.style.display = 'none';
                return;
            }
            
            block.style.display = 'block';
            
            const btnBetou = document.getElementById('btn-betou-kouenda');
            if (btnBetou) {
                if (salon.joueurs.some(j => j && j.nom === userNom)) {
                    btnBetou.style.display = 'block';
                    btnBetou.onclick = function() {
                        if (salon.code) {
                            localStorage.setItem('codeSalon', salon.code);
                            sessionStorage.setItem('codeSalon', salon.code);
                            window.location.href = 'jeux-encours.html?code=' + encodeURIComponent(salon.code);
                        } else {
                            window.location.href = 'jeux-encours.html';
                        }
                    };
                } else {
                    btnBetou.style.display = 'none';
                }
            }
            
            document.getElementById('member-salon-code').textContent = `Code du salon : ${salon.code}`;
            
            const hote = salon.joueurs[0];
            let nomAffiche = hote && hote.nom ? hote.nom : 'Hôte';
            document.getElementById('member-host-info').innerHTML = `
                <div class="player-block">
                    <img src="${hote && hote.photo ? hote.photo : '../assets/avatar-default.png'}" class="avatar host" alt="Avatar Hôte">
                    <div class="player-name">${nomAffiche} <span style="font-size:0.8em;color:#ff00ff;">(Hôte)</span></div>
                </div>
            `;
            
            const playersList = document.getElementById('member-players-list');
            playersList.innerHTML = '';
            const totalSlots = salon.maxJoueurs;
            
            for (let i = 1; i < totalSlots; i++) {
                const joueur = salon.joueurs[i];
                if (joueur && joueur.nom && joueur.photo) {
                    let avatarAttrs = `src="${joueur.photo}" class="avatar player" alt="Avatar joueur"`;
                    if (userNom === (hote && hote.nom)) {
                        avatarAttrs += ` data-nom="${joueur.nom}" style="cursor:pointer;"`;
                    }
                    playersList.innerHTML += `
                        <div class="player-block">
                            <img ${avatarAttrs} />
                            <div class="player-name">${joueur.nom}${joueur.nom === userNom ? ' <span style="color:#ffe600;font-size:0.9em;">(Vous)</span>' : ''}</div>
                        </div>
                    `;
                } else {
                    playersList.innerHTML += `
                        <div class="player-slot">
                            <div class="photo-placeholder" style="width:70px;height:70px;border-radius:50%;background:rgba(0,255,249,0.13);border:2.5px solid #00fff9;box-shadow:0 0 16px #00fff966,0 0 32px #ff00ff99;margin:0 auto 0.5em auto;"></div>
                            <div class="slot-status">Disponible</div>
                        </div>
                    `;
                }
            }
            
            if (userNom === (hote && hote.nom)) {
                setTimeout(() => {
                    document.querySelectorAll('.avatar.player[data-nom]').forEach(img => {
                        img.onclick = function(e) {
                            const nomARetirer = this.getAttribute('data-nom');
                            showKickPopin(nomARetirer, salon.code, () => renderMemberSalonBlock());
                        };
                    });
                }, 100);
            }

            const joueursConnectes = salon.joueurs.filter(j => j && j.nom && j.photo).length;
            const salonMsg = document.getElementById('member-salon-message');
            if (joueursConnectes < totalSlots) {
                salonMsg.textContent = 'En attente des joueurs...';
                salonMsg.style.color = '#00fff9';
            } else {
                salonMsg.textContent = 'Place au jeu !';
                salonMsg.style.color = '#ff00ff';
            }
        }

        function showKickPopin(nom, code, onKick) {
            let old = document.getElementById('kick-popin');
            if (old) old.remove();
            
            const popin = document.createElement('div');
            popin.id = 'kick-popin';
            popin.innerHTML = `
                <div style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(10,16,40,0.92);z-index:3000;display:flex;align-items:center;justify-content:center;">
                    <div style="background:#181c3a;padding:2.2em 2.5em 2em 2.5em;border-radius:1.2em;box-shadow:0 0 32px #00fff966,0 0 0 2px #00fff933,0 0 80px 8px #ff00ff22;text-align:center;max-width:90vw;border:1.5px solid #00fff9;">
                        <div style='font-size:1.18em;color:#ff0055;font-weight:bold;margin-bottom:1.2em;'>Retirer <span style="color:#ffe600;">${nom}</span> du salon ?</div>
                        <button id="btn-kick-confirm" style="background:linear-gradient(90deg,#ff0055 0%,#ffe600 100%);color:#181c3a;font-weight:bold;font-size:1.13em;padding:0.7em 2.2em;border-radius:0.8em;border:none;box-shadow:0 0 16px #ff0055cc,0 0 32px #ffe60099;cursor:pointer;">Retirer</button>
                        <button id="btn-kick-cancel" style="margin-left:1.2em;background:linear-gradient(90deg,#222 60%,#00fff9 100%);color:#fff;font-weight:bold;font-size:1.13em;padding:0.7em 2.2em;border-radius:0.8em;border:none;box-shadow:0 0 16px #00fff966,0 0 32px #00fff933;cursor:pointer;">Annuler</button>
                    </div>
                </div>
            `;
            document.body.appendChild(popin);
            
            document.getElementById('btn-kick-cancel').onclick = () => popin.remove();
            document.getElementById('btn-kick-confirm').onclick = async function() {
                try {
                    const res = await fetch('../backend/kick_player.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ code, nom })
                    });
                    const result = await res.json();
                    popin.remove();
                    if (result && result.success) {
                        if (typeof onKick === 'function') onKick();
                    } else {
                        alert(result && result.error ? result.error : 'Erreur lors du retrait');
                    }
                } catch (e) {
                    popin.remove();
                    alert('Erreur réseau lors du retrait');
                }
            };
        }

        renderMemberSalonBlock();
        
        setInterval(renderMemberSalonBlock, 3000);

        const scrollToTopBtn = document.getElementById('scroll-to-top');
        
        if (scrollToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.add('show');
                } else {
                    scrollToTopBtn.classList.remove('show');
                }
            });
            
            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        const chatInput = document.getElementById('chat-input');
        const chatSendBtn = document.getElementById('chat-send-btn');
        const chatMessages = document.getElementById('chat-messages');

        function addMessage(text, isAdmin = false, timestamp = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${isAdmin ? 'admin' : 'user'}`;

            if (isAdmin) {
                const avatar = document.createElement('img');
                avatar.src = '../assets/avatar-default.png';
                avatar.alt = 'Admin';
                avatar.className = 'chat-admin-avatar';
                messageDiv.appendChild(avatar);
            }

            const authorDiv = document.createElement('div');
            authorDiv.className = 'chat-message-author';

            const timeStr = timestamp ? new Date(timestamp).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '';
            authorDiv.textContent = isAdmin ? 'Admin' + (timeStr ? ' • ' + timeStr : '') : 'Vous' + (timeStr ? ' • ' + timeStr : '');

            const textDiv = document.createElement('div');
            textDiv.textContent = text;

            messageDiv.appendChild(authorDiv);
            messageDiv.appendChild(textDiv);
            chatMessages.appendChild(messageDiv);

            chatMessages.scrollTop = chatMessages.scrollHeight;
                const style = document.createElement('style');
                style.textContent = `
                    .chat-admin-avatar {
                        width: 44px;
                        height: 44px;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 2.5px solid #ffe600;
                        box-shadow: 0 0 16px #ffe60099, 0 0 0 4px #181c3a;
                        margin-bottom: 0.3em;
                        display: block;
                        margin-left: auto;
                        margin-right: auto;
                    }
                    .chat-message.admin {
                        position: relative;
                        padding-top: 0.7em;
                        padding-bottom: 0.7em;
                        padding-left: 0.7em;
                    }
                `;
                document.head.appendChild(style);
        }

        function loadChatMessages() {
            fetch('../backend/user_chat.php')
                .then(r => r.json())
                .then(data => {
                    console.log('Messages reçus côté utilisateur:', data);
                    
                    if (data.success && data.messages) {
                        const scrollPos = chatMessages.scrollTop;
                        const isAtBottom = chatMessages.scrollHeight - chatMessages.scrollTop <= chatMessages.clientHeight + 50;
                        
                        chatMessages.innerHTML = '';
                        
                        console.log('Nombre de messages à afficher:', data.messages.length);
                        
                        data.messages.forEach(msg => {
                            console.log('Message:', msg.message, 'isAdmin:', msg.is_from_admin);
                            addMessage(msg.message, msg.is_from_admin == 1, msg.created_at);
                        });
                        
                        if (isAtBottom || data.messages.length > 0) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        } else {
                            chatMessages.scrollTop = scrollPos;
                        }
                    } else {
                        console.error('Erreur chargement messages:', data);
                    }
                })
                .catch(err => console.error('Erreur chargement messages:', err));
        }

        function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) {
                return;
            }
            
            fetch('../backend/user_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: message })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    chatInput.value = '';
                    loadChatMessages(); 
                } else {
                    alert('Erreur: ' + (data.error || 'Envoi échoué'));
                }
            })
            .catch(err => {
                console.error('Erreur envoi message:', err);
                alert('Erreur réseau lors de l\'envoi');
            });
        }
        
        loadChatMessages();
        
        setInterval(loadChatMessages, 3000);

        if (chatSendBtn) {
            chatSendBtn.addEventListener('click', sendMessage);
        }

        if (chatInput) {
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }
    </script>
</body>
</html>
