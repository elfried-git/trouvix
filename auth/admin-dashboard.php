<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        body { margin: 0; padding: 0; }
        .admin-sidebar {
            width: 250px;
            background: #181c3a;
            min-height: 100vh;
            box-shadow: 2px 0 24px #00fff933;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.5em 1em 1em 1em;
            gap: 1.1em;
        }
        .admin-sidebar .logo-text {
            font-size: 2em;
            color: #a259ff;
            letter-spacing: 0.08em;
            margin-bottom: 1.5em;
        }
        .avatar-round {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 12px #FFD60099;
            background: #222;
        }
        .admin-sidebar a {
            width: 100%;
            margin-bottom: 0;
            padding: 0.85em 1.1em;
            display: block;
            text-align: left;
            font-size: 1.13em;
            font-weight: 500;
            letter-spacing: 0.01em;
            transition: background 0.2s;
        }
        .admin-sidebar a:last-child {
            margin-bottom: 0;
        }
        .admin-header {
            width: 100%;
            background: rgba(0,255,249,0.07);
            box-shadow: 0 2px 24px #00fff933;
            padding: 1.2em 2em;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg,#0a0a23 0%,#1a2236 100%);
        }
        .admin-cards {
            display: flex;
            gap: 2em;
            justify-content: center;
            margin: 2em 0 2em 0;
        }
        .admin-card {
            flex: 1;
            min-width: 180px;
            background: rgba(0,255,249,0.10);
            border-radius: 1em;
            padding: 1.5em 1em;
            box-shadow: 0 0 16px #00fff966;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .admin-card .hote-btn { margin-bottom: 0.7em; }
        .admin-table-section {
            max-width: 1200px;
            margin: 0 auto 2em auto;
        }
        .admin-table-section h2 {
            color: #0ff1ce;
            text-align: center;
            margin-bottom: 1em;
        }
        .admin-table-container {
            background: rgba(0,255,249,0.07);
            border-radius: 1em;
            padding: 2em 2em 2em 2em;
            box-shadow: 0 0 16px #00fff933;
            overflow-x: auto;
        }
        .admin-table {
            width: 100%;
            min-width: 1100px;
            border-collapse: collapse;
            color: #eaf6fb;
            font-size: 1.1em;
        }
        .admin-table th, .admin-table td {
            padding: 0.9em 0.7em;
            text-align: center;
        }
        .admin-table thead tr {
            background: rgba(0,255,249,0.15);
        }

        /* ===== UNIFIED BUTTON STYLES ===== */
        .unified-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85em 2em;
            font-size: 1.05em;
            font-weight: 700;
            border-radius: 0.9em;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            min-width: 120px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .unified-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .unified-btn:active {
            transform: translateY(0);
        }

        /* Primary button - Cyan/Purple gradient */
        .btn-primary {
            background: linear-gradient(90deg, #00fff9 0%, #a259ff 100%);
            color: #181c3a;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #00e6e0 0%, #914deb 100%);
        }

        /* Danger button - Red gradient */
        .btn-danger {
            background: linear-gradient(135deg, #ff2d55, #ff0055);
            color: #fff;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #e6284d, #e6004d);
        }

        /* Secondary button - Gray/Blue */
        .btn-secondary {
            background: rgba(138, 233, 253, 0.25);
            color: #8be9fd;
            border: 2px solid #8be9fd;
        }

        .btn-secondary:hover {
            background: rgba(138, 233, 253, 0.4);
        }

        /* Small danger button for table actions */
        .btn-danger-small {
            padding: 0.5em 1em;
            font-size: 0.95em;
            min-width: auto;
            background: #e74c3c;
            color: #fff;
        }

        .btn-danger-small:hover {
            background: #c0392b;
        }

        /* Success button - Green */
        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: #fff;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #229954, #28b463);
        }

        /* Keep legacy classes for compatibility */
        .btn-modifier {
            background: #0ff1ce;
            color: #181c3a;
            border: none;
            padding: 0.45em 1.1em;
            border-radius: 0.4em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-modifier:hover {
            background: #00bfae;
        }
        .btn-supprimer {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 0.45em 1.1em;
            border-radius: 0.4em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-supprimer:hover {
            background: #c0392b;
        }
        .btn-valider {
            background: #27ae60;
            color: #fff;
            border: none;
            padding: 0.35em 0.9em;
            border-radius: 0.4em;
            font-weight: 600;
            cursor: pointer;
            margin-left: 0.3em;
        }
        .btn-annuler {
            background: #aaa;
            color: #181c3a;
            border: none;
            padding: 0.35em 0.9em;
            border-radius: 0.4em;
            font-weight: 600;
            cursor: pointer;
            margin-left: 0.3em;
        }
        .online-dot {
            display:inline-block;
            width:13px;
            height:13px;
            border-radius:50%;
            background:#00ff00;
            margin-right:7px;
            box-shadow:0 0 8px #0f0;
            animation: blink 1s infinite;
            vertical-align:middle;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }
    </style>
</head>
<body class="body" style="margin:0;padding:0;">
    <div style="display:flex;min-height:100vh;">
        <nav class="admin-sidebar">
        <div class="logo-text">TROUVIX</div>
        <img src="../assets/avatar-default.png" alt="Avatar" class="avatar-round" style="display:block;margin:0 auto 1.5em auto;">
    <a href="notification-detail.html" class="hote-btn" id="btn-notifications" style="position:relative;">Contact
        <span id="notif-badge" style="display:none;position:absolute;top:-10px;right:-10px;min-width:22px;height:22px;background:#ff2d55;color:#fff;border-radius:50%;font-size:1em;font-weight:bold;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(248, 0, 0, 0.2);border:2px solid #fff;z-index:2;">0</span>
    </a>
    <a href="#" class="hote-btn">Dashboard</a>
    <a href="#" class="hote-btn" id="btn-utilisateurs">Utilisateurs</a>
    <a href="#" class="hote-btn" id="forum-link" style="position:relative;">Forum
        <span id="forum-badge" style="display:none;position:absolute;top:-10px;right:-10px;min-width:22px;height:22px;background:#FFD600;color:#181c3a;border-radius:50%;font-size:1em;font-weight:bold;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px #FFD60066;border:2px solid #fff;z-index:2;">0</span>
    </a>
    <a href="#" class="hote-btn" id="btn-chat" style="position:relative;">Chat
        <span id="chat-badge" style="display:none;position:absolute;top:-10px;right:-10px;min-width:22px;height:22px;background:#ff2d55;color:#fff;border-radius:50%;font-size:1em;font-weight:bold;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(248, 0, 0, 0.2);border:2px solid #fff;z-index:2;">0</span>
    </a>
    <a href="#" class="hote-btn" id="btn-salons">Salons</a>
        </nav>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var notifBtn = document.getElementById('btn-notifications');
            var notifPanel = document.getElementById('notifications-panel');
            if (notifBtn && notifPanel) {
                notifBtn.onclick = function(e) {
                    e.preventDefault();
                    notifPanel.style.display = (notifPanel.style.display==='none'||!notifPanel.style.display)?'block':'none';
                    if (notifPanel.style.display==='block') {
                        fetchNotifications().then(renderNotifications);
                    }
                };
            }
            var badge = document.getElementById('notif-badge');
            if (badge && notifPanel) {
                badge.onclick = function(e) {
                    e.preventDefault();
                    notifPanel.style.display = (notifPanel.style.display==='none'||!notifPanel.style.display)?'block':'none';
                    if (notifPanel.style.display==='block') {
                        fetchNotifications().then(renderNotifications);
                    }
                };
                badge.style.cursor = 'pointer';
            }
        });
        </script>
        <div class="admin-main">
            <header class="admin-header">
                <h1 style="color:#0ff1ce;font-size:2em;margin:0;">Espace Administration</h1>
                <div style="display:flex;align-items:center;gap:2em;margin-right:6vw;">
                    <div id="admin-info" style="color:#eaf6fb;font-size:1.08em;"></div>
                        <a href="../backend/logout.php" class="unified-btn btn-primary" style="
                            margin-left:1.5em;
                            background: linear-gradient(90deg, #00fff9 0%, #a259ff 100%);
                            color: #181c3a;
                            text-decoration: none;
                        ">Déconnexion</a>
                </div>
            </header>
            <script>
            </script>
            <div class="admin-cards">
                <div style="display:flex;gap:2em;width:100%;">
                    <div class="admin-card">
                        <div style="font-size:2.2em;font-weight:bold;color:#0ff1ce;" id="stat-users">...</div>
                        <div style="color:#eaf6fb;">Utilisateurs inscrits</div>
                    </div>
                    <div class="admin-card">
                        <div style="font-size:2.2em;font-weight:bold;color:#0ff1ce;" id="stat-online">...</div>
                        <div style="color:#eaf6fb;">Connectés en temps réel</div>
                    </div>
                    <div class="admin-card">
                        <div style="font-size:2.2em;font-weight:bold;color:#0ff1ce;">...</div>
                        <div style="color:#eaf6fb;">Publications Forum</div>
                    </div>
                </div>
                <div style="display:flex;gap:2em;width:100%;margin-top:2em;">
                    <div class="admin-card">
                        <div style="font-size:2.2em;font-weight:bold;color:#0ff1ce;">...</div>
                        <div style="color:#eaf6fb;">Salons</div>
                    </div>
                </div>
            </div>
            <section class="admin-table-section" id="users-section">
                <h2>Utilisateurs connectés (temps réel)</h2>
                <div class="admin-table-container">
                    <table class="admin-table" id="online-users-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Dernière connexion</th>
                            <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody id="online-users-tbody">
                            <tr><td colspan="5" style="text-align:center;">Chargement...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="admin-table-section" id="salons-section" style="display:none;">
                <h2>Salons créés par les hôtes</h2>
                <div class="admin-table-container">
                    <table class="admin-table" id="salons-table">
                        <thead>
                            <tr>
                                <th>Nom du salon</th>
                                <th>Code</th>
                                <th>Hôte</th>
                                <th>Max joueurs</th>
                                <th>Date de création</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody id="salons-tbody">
                            <tr><td colspan="7" style="text-align:center;">Chargement...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-table-section" id="chat-section" style="display:none;">
                <h2>💬 Conversations avec les Utilisateurs</h2>
                <div style="max-width: 1400px; margin: 0 auto;">
                    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5em; min-height: 600px;">
                        
                        <!-- Liste des utilisateurs -->
                        <div style="background: rgba(24,28,58,0.95); border-radius: 1.5em; padding: 1.5em; box-shadow: 0 0 32px #00fff933, 0 0 0 2px #00fff933;">
                            <h3 style="color: #0ff1ce; margin: 0 0 1em 0; font-size: 1.2em;">👥 Utilisateurs</h3>
                            <div id="users-list" style="display: flex; flex-direction: column; gap: 0.5em; max-height: 500px; overflow-y: auto;">
                                <div style="text-align: center; color: #8be9fd; padding: 2em;">
                                    Chargement...
                                </div>
                            </div>
                        </div>
                        
                        <!-- Zone de conversation -->
                        <div style="background: rgba(24,28,58,0.95); border-radius: 1.5em; padding: 2em; box-shadow: 0 0 32px #00fff933, 0 0 0 2px #00fff933; display: flex; flex-direction: column;">
                            
                            <!-- En-tête conversation -->
                            <div id="chat-header" style="margin-bottom: 1.5em; padding-bottom: 1em; border-bottom: 2px solid rgba(0,255,249,0.3);">
                                <div style="color: #8be9fd; font-size: 1.1em;">
                                    Sélectionnez un utilisateur pour démarrer une conversation
                                </div>
                            </div>
                            
                            <!-- Messages -->
                            <div id="admin-chat-messages" style="flex: 1; background: rgba(0,0,0,0.4); border-radius: 1em; padding: 1.5em; max-height: 400px; overflow-y: auto; margin-bottom: 1.5em; border: 2px solid rgba(0,255,249,0.3);">
                                <div style="text-align: center; color: #8be9fd; padding: 2em;">
                                    Aucune conversation sélectionnée
                                </div>
                            </div>

                            <!-- Zone de saisie -->
                            <div id="chat-input-area" style="display: none;">
                                <div style="display: flex; gap: 1em; align-items: flex-end;">
                                    <div style="flex: 1;">
                                        <label style="display: block; color: #0ff1ce; font-weight: bold; margin-bottom: 0.5em; font-size: 1.05em;">Votre réponse :</label>
                                        <textarea id="admin-chat-input" placeholder="Tapez votre réponse ici..." style="width: 100%; min-height: 80px; background: rgba(24,28,58,0.8); color: #fff; border: 2px solid #00fff9; border-radius: 0.8em; padding: 1em; font-size: 1em; resize: vertical; font-family: inherit; outline: none; box-sizing: border-box;" onkeydown="if(event.key==='Enter' && event.ctrlKey) sendAdminReply();"></textarea>
                                        <small style="color: #8be9fd; font-size: 0.9em;">Appuyez sur Ctrl+Entrée pour envoyer</small>
                                    </div>
                                    <button id="admin-chat-send" onclick="sendAdminReply()" class="unified-btn btn-primary">
                                        Envoyer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 1em; padding: 1em; background: rgba(255,230,0,0.1); border-left: 4px solid #ffe600; border-radius: 0.5em;">
                        <p style="color: #ffe600; margin: 0; font-size: 0.95em;">
                            <strong>Astuce :</strong> Cliquez sur un utilisateur pour voir votre conversation et lui répondre directement.
                        </p>
                    </div>
                </div>
            </section>

        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnUtilisateurs = document.getElementById('btn-utilisateurs');
        const btnSalons = document.getElementById('btn-salons');
        const btnChat = document.getElementById('btn-chat');
        const usersSection = document.getElementById('users-section');
        const salonsSection = document.getElementById('salons-section');
        const chatSection = document.getElementById('chat-section');
        
        usersSection.style.display = '';
        salonsSection.style.display = 'none';
        chatSection.style.display = 'none';
        
        let chatRefreshInterval = null;
        
        btnUtilisateurs.addEventListener('click', function(e) {
            e.preventDefault();
            usersSection.style.display = '';
            salonsSection.style.display = 'none';
            chatSection.style.display = 'none';
            
            // Arrêter le rafraîchissement des messages
            if (chatRefreshInterval) {
                clearInterval(chatRefreshInterval);
                chatRefreshInterval = null;
            }
            if (usersListRefreshInterval) {
                clearInterval(usersListRefreshInterval);
                usersListRefreshInterval = null;
            }
        });
        
        btnSalons.addEventListener('click', function(e) {
            e.preventDefault();
            salonsSection.style.display = '';
            usersSection.style.display = 'none';
            chatSection.style.display = 'none';
            fetchSalons();
            
            // Arrêter le rafraîchissement des messages
            if (chatRefreshInterval) {
                clearInterval(chatRefreshInterval);
                chatRefreshInterval = null;
            }
            if (usersListRefreshInterval) {
                clearInterval(usersListRefreshInterval);
                usersListRefreshInterval = null;
            }
        });
        
        btnChat.addEventListener('click', function(e) {
            e.preventDefault();
            chatSection.style.display = '';
            usersSection.style.display = 'none';
            salonsSection.style.display = 'none';
            
            // Charger la liste des utilisateurs
            loadUsersList();
            
            // Rafraîchir la liste des utilisateurs toutes les 3 secondes
            if (usersListRefreshInterval) clearInterval(usersListRefreshInterval);
            usersListRefreshInterval = setInterval(loadUsersList, 3000);
        });

        const forumLink = document.getElementById('forum-link');
        const forumBadge = document.getElementById('forum-badge');
        const adminMain = document.querySelector('.admin-main');
        let forumSection = null;
        forumLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.open('http://localhost/Trouvix/forum/admin-login.html', '_blank');
        });

        // Badge Forum : nombre de sujets non lus
        function updateForumBadge() {
            fetch('../backend/get_topics.php')
                .then(r => r.json())
                .then(list => {
                    if (Array.isArray(list)) {
                        const unread = list.filter(t => !t.admin_read).length;
                        if (unread > 0) {
                            forumBadge.textContent = unread;
                            forumBadge.style.display = 'flex';
                        } else {
                            forumBadge.style.display = 'none';
                        }
                    }
                });
        }
        updateForumBadge();
        setInterval(updateForumBadge, 5000);
        
        // Initialiser le badge de chat dès le chargement
        updateChatBadge();
        setInterval(updateChatBadge, 2000); // Vérifier toutes les 2 secondes
        
        // Initialiser le badge de notifications
        updateNotifBadge();
        setInterval(updateNotifBadge, 3000);
    });
        var ADMIN_SESSION = null;
        fetch('../backend/get_user_info.php')
            .then(res => res.ok ? res.json() : null)
            .then(data => {
                if (data && data.nom && data.email) {
                    ADMIN_SESSION = data;
                    document.getElementById('admin-info').innerHTML =
                        `<span style="color:#0ff1ce;font-weight:bold;">${data.nom}</span> <span style="color:#fff;">(${data.email})</span>`;
                }
            });
    function fetchOnlineUsers() {
        fetch('../backend/online_users.php')
            .then(response => response.json())
            .then(users => {
                const tbody = document.getElementById('online-users-tbody');
                tbody.innerHTML = '';
                let totalOnline = 0;
                let adminOnline = false;
                let adminEmail = 'atexotest20@gmail.com';
                if (users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Aucun utilisateur</td></tr>';
                } else {
                    let adminUser = null;
                    let otherUsers = [];
                    users.forEach(user => {
                        if (adminEmail && user.email && user.email.toLowerCase() === adminEmail) {
                            adminUser = user;
                        } else {
                            otherUsers.push(user);
                        }
                    });
                    if (adminUser) {
                        let nomCell = `<span style='color:#e74c3c;font-weight:bold;'>${adminUser.nom}</span>`;
                        let emailCell = `<span style='color:#e74c3c;font-weight:bold;'>${adminUser.email}</span>`;
                        let statut = '';
                        if (adminUser.is_online) {
                            statut = '<span class="online-dot" style="background:#e74c3c;box-shadow:0 0 8px #e74c3c;"></span> <span style="color:#e74c3c;font-weight:bold;">En ligne</span>';
                        }
                        let last = adminUser.last_activity ? adminUser.last_activity : '-';
                        tbody.innerHTML += `<tr>
                            <td>${nomCell}</td>
                            <td>${emailCell}</td>
                            <td>${statut}</td>
                            <td>${adminUser.is_online ? '-' : last}</td>
                            <td></td>
                        </tr>`;
                    }
                    otherUsers.forEach(user => {
                        let nomCell = user.nom;
                        let emailCell = user.email;
                        let statut;
                        if (user.is_online) {
                            statut = '<span class="online-dot"></span> <span style="color:#0f0;font-weight:bold;">En ligne</span>';
                            totalOnline++;
                        } else {
                            statut = '<span style="color:#aaa;">Hors ligne</span>';
                        }
                        let last = user.last_activity ? user.last_activity : '-';
                        tbody.innerHTML += `<tr data-email="${user.email}">
                            <td>${nomCell}</td>
                            <td>${emailCell}</td>
                            <td>${statut}</td>
                            <td>${user.is_online ? '-' : last}</td>
                            <td><button class="btn-supprimer-user unified-btn btn-danger-small">Supprimer</button></td>
                        </tr>`;
                    });
                }
                tbody.querySelectorAll('.btn-supprimer-user').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const tr = this.closest('tr');
                        const email = tr.getAttribute('data-email');
                        if (!email) return;
                        let modal = document.getElementById('modal-suppr-user');
                        if (modal) modal.remove();
                        modal = document.createElement('div');
                        modal.id = 'modal-suppr-user';
                        modal.style = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(10,16,40,0.85);z-index:2000;display:flex;align-items:center;justify-content:center;';
                        modal.innerHTML = `
                            <div style="background:#181c3a;padding:2em 2.5em 2em 2.5em;border-radius:1.2em;box-shadow:0 0 32px #00fff966,0 0 0 2px #00fff933;text-align:center;max-width:90vw;min-width:320px;">
                                <div style="font-size:1.3em;color:#e74c3c;font-weight:bold;margin-bottom:1.2em;letter-spacing:0.02em;">Supprimer cet utilisateur ?</div>
                                <div style="color:#eaf6fb;margin-bottom:1.5em;">Cette action est irréversible.</div>
                                <button id="btn-confirmer-suppr-user" class="unified-btn btn-danger" style="margin-right:1em;">Supprimer</button>
                                <button id="btn-annuler-suppr-user" class="unified-btn btn-secondary">Annuler</button>
                            </div>
                        `;
                        document.body.appendChild(modal);
                        document.getElementById('btn-annuler-suppr-user').onclick = function() {
                            modal.remove();
                        };
                        document.getElementById('btn-confirmer-suppr-user').onclick = function() {
                            fetch('../backend/delete_user.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ email })
                            })
                            .then(r => r.json())
                            .then(res => {
                                if (res.success) {
                                    tr.remove();
                                    modal.remove();
                                } else {
                                    alert(res.error || 'Erreur lors de la suppression');
                                    modal.remove();
                                }
                            })
                            .catch(() => {
                                alert('Erreur réseau');
                                modal.remove();
                            });
                        };
                    });
                });
                document.getElementById('stat-users').textContent = users.length;
                document.getElementById('stat-online').textContent = totalOnline;
                const adminStatus = document.getElementById('admin-status');
                if (adminStatus) {
                    if (adminOnline) {
                        adminStatus.innerHTML = '<span style="color:#e74c3c;">●</span> <span style="color:#e74c3c;">En ligne (admin)</span>';
                    } else {
                        adminStatus.innerHTML = '<span style="color:#aaa;">●</span> <span style="color:#aaa;">Hors ligne (admin)</span>';
                    }
                }
            })
            .catch(() => {
                document.getElementById('online-users-tbody').innerHTML = '<tr><td colspan="4" style="text-align:center;">Erreur de chargement</td></tr>';
            });
    }
    fetchOnlineUsers();
    setInterval(fetchOnlineUsers, 1000);
    setInterval(function() {
        fetch('../backend/update_activity.php', { credentials: 'include' });
    }, 5000);

    // Suppression de la déconnexion automatique sur beforeunload
    </script>
    <script src="../js/admin-dashboard.js"></script>
    <script>
    // Fonction pour mettre à jour le compteur d'utilisateurs en ligne
    function updateOnlineCount() {
        fetch('../backend/online_users.php')
            .then(r => r.json())
            .then(users => {
                const count = users.length;
                document.getElementById('online-count-chat').textContent = count;
            })
            .catch(err => console.error('Erreur compteur:', err));
    }

    // Fonction pour envoyer un message admin
    function sendAdminMessage() {
        const input = document.getElementById('admin-chat-input');
        const message = input.value.trim();
        
        if (!message) {
            alert('Veuillez saisir un message');
            return;
        }

        // Ajouter le message dans l'interface admin
        addAdminChatMessage(message, true);
        
        // Envoyer le message via localStorage pour simulation
        // Dans une vraie application, vous utiliseriez WebSocket ou une API
        const broadcastMessage = {
            type: 'admin_broadcast',
            message: message,
            timestamp: Date.now(),
            from: 'Admin'
        };
        
        // Stocker dans localStorage pour que les utilisateurs puissent le récupérer
        localStorage.setItem('admin_broadcast_' + Date.now(), JSON.stringify(broadcastMessage));
        
        // Vider le champ
        input.value = '';
        
        // Afficher une confirmation
        showNotification('Message envoyé à tous les utilisateurs connectés !', 'success');
    }

    // Fonction pour ajouter un message dans la zone de chat
    function addAdminChatMessage(message, isAdmin = false, userName = 'Utilisateur', timestamp = null) {
        const container = document.getElementById('admin-chat-messages');
        
        // Supprimer le message "Aucun message"
        if (container.querySelector('[style*="text-align: center"]')) {
            container.innerHTML = '';
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = 'margin-bottom: 1em; padding: 1em; border-radius: 0.8em; ' + 
            (isAdmin ? 'background: rgba(0,255,249,0.15); border-left: 4px solid #00fff9;' : 'background: rgba(255,230,0,0.15); border-left: 4px solid #ffe600;');
        
        const time = timestamp ? new Date(timestamp).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        
        messageDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5em;">
                <strong style="color: ${isAdmin ? '#00fff9' : '#ffe600'};">${isAdmin ? '👨‍💼 Admin' : '👤 ' + userName}</strong>
                <span style="color: #8be9fd; font-size: 0.9em;">${time}</span>
            </div>
            <div style="color: #fff;">${message}</div>
        `;
        
        container.appendChild(messageDiv);
        container.scrollTop = container.scrollHeight;
    }

    // Fonction pour afficher une notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 10000;
            padding: 1em 1.5em; border-radius: 0.8em;
            background: ${type === 'success' ? 'rgba(0,255,0,0.9)' : 'rgba(0,255,249,0.9)'};
            color: #181c3a; font-weight: bold; font-size: 1.05em;
            box-shadow: 0 0 24px rgba(0,255,249,0.6);
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Style pour les animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // Variables globales pour le chat
    let lastMessageId = 0;
    let selectedUserId = null;
    let selectedUserName = '';
    let chatRefreshInterval = null;
    let usersListRefreshInterval = null;
    
    // Fonction pour charger la liste des utilisateurs avec messages
    function loadUsersList() {
        fetch('../backend/admin_chat_messages.php')
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.messages) return;
                
                // Grouper les messages par utilisateur
                const userMessages = {};
                data.messages.forEach(msg => {
                    if (!userMessages[msg.user_id]) {
                        userMessages[msg.user_id] = {
                            userId: msg.user_id,
                            userName: msg.user_name,
                            messages: [],
                            unreadCount: 0,
                            lastMessage: null
                        };
                    }
                    userMessages[msg.user_id].messages.push(msg);
                    if (msg.is_from_admin == 0 && msg.is_read == 0) {
                        userMessages[msg.user_id].unreadCount++;
                    }
                    userMessages[msg.user_id].lastMessage = msg;
                });
                
                const usersList = document.getElementById('users-list');
                if (Object.keys(userMessages).length === 0) {
                    usersList.innerHTML = '<div style="text-align: center; color: #8be9fd; padding: 2em;">Aucun message</div>';
                    return;
                }
                
                usersList.innerHTML = '';
                
                // Trier par dernier message
                const sortedUsers = Object.values(userMessages).sort((a, b) => {
                    return new Date(b.lastMessage.created_at) - new Date(a.lastMessage.created_at);
                });
                
                sortedUsers.forEach(user => {
                    const userCard = document.createElement('div');
                    userCard.className = 'user-card';
                    userCard.dataset.userId = user.userId;
                    userCard.style.cssText = `
                        padding: 1em;
                        background: ${selectedUserId == user.userId ? 'rgba(0,255,249,0.2)' : 'rgba(0,255,249,0.05)'};
                        border-left: 4px solid ${user.unreadCount > 0 ? '#ffe600' : '#00fff9'};
                        border-radius: 0.5em;
                        cursor: pointer;
                        transition: all 0.3s;
                        position: relative;
                    `;
                    
                    const lastMsgPreview = user.lastMessage.message.length > 40 ? 
                        user.lastMessage.message.substring(0, 40) + '...' : 
                        user.lastMessage.message;
                    
                    userCard.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5em;">
                            <strong style="color: #00fff9; font-size: 1.05em;">👤 ${user.userName}</strong>
                            ${user.unreadCount > 0 ? `<span style="background: #ffe600; color: #181c3a; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.85em; font-weight: bold;">${user.unreadCount}</span>` : ''}
                        </div>
                        <div style="color: #8be9fd; font-size: 0.9em; opacity: 0.8;">${lastMsgPreview}</div>
                        <div style="color: #666; font-size: 0.8em; margin-top: 0.3em;">${new Date(user.lastMessage.created_at).toLocaleString('fr-FR', {hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit'})}</div>
                    `;
                    
                    userCard.onclick = () => selectUser(user.userId, user.userName);
                    userCard.onmouseenter = () => {
                        if (selectedUserId != user.userId) {
                            userCard.style.background = 'rgba(0,255,249,0.15)';
                        }
                    };
                    userCard.onmouseleave = () => {
                        if (selectedUserId != user.userId) {
                            userCard.style.background = 'rgba(0,255,249,0.05)';
                        }
                    };
                    
                    usersList.appendChild(userCard);
                });
                
                // Mettre à jour le badge
                const totalUnread = sortedUsers.reduce((sum, u) => sum + u.unreadCount, 0);
                updateChatBadge(totalUnread);
            })
            .catch(err => console.error('Erreur chargement utilisateurs:', err));
    }
    
    // Fonction pour sélectionner un utilisateur
    function selectUser(userId, userName) {
        selectedUserId = userId;
        selectedUserName = userName;
        
        // Mettre à jour l'UI
        document.querySelectorAll('.user-card').forEach(card => {
            if (card.dataset.userId == userId) {
                card.style.background = 'rgba(0,255,249,0.2)';
            } else {
                card.style.background = 'rgba(0,255,249,0.05)';
            }
        });
        
        // Afficher l'en-tête de conversation
        document.getElementById('chat-header').innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 1em;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #00fff9, #ff00ff); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">
                        👤
                    </div>
                    <div>
                        <div style="color: #00fff9; font-size: 1.3em; font-weight: bold;">${userName}</div>
                        <div style="color: #8be9fd; font-size: 0.9em;">Conversation privée</div>
                    </div>
                </div>
                <button onclick="deleteConversation(${userId}, '${userName}')" class="unified-btn btn-danger">
                    Supprimer la conversation
                </button>
            </div>
        `;
        
        // Afficher la zone de saisie
        document.getElementById('chat-input-area').style.display = 'block';
        
        // Charger les messages de cette conversation
        loadConversation(userId);
        
        // Rafraîchir toutes les 2 secondes
        if (chatRefreshInterval) clearInterval(chatRefreshInterval);
        chatRefreshInterval = setInterval(() => loadConversation(userId), 2000);
    }
    
    // Fonction pour charger la conversation avec un utilisateur
    function loadConversation(userId) {
        fetch('../backend/admin_chat_messages.php')
            .then(r => r.json())
            .then(data => {
                if (!data.success || !data.messages) return;
                
                // Filtrer les messages de cet utilisateur
                const userMessages = data.messages.filter(m => m.user_id == userId);
                
                const container = document.getElementById('admin-chat-messages');
                
                if (userMessages.length === 0) {
                    container.innerHTML = '<div style="text-align: center; color: #8be9fd; padding: 2em;">Aucun message dans cette conversation</div>';
                    return;
                }
                
                // Sauvegarder la position de scroll
                const scrollPos = container.scrollTop;
                const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 50;
                
                // Vérifier les nouveaux messages
                const latestId = Math.max(...userMessages.map(m => m.id));
                const hasNewMessages = latestId > lastMessageId;
                lastMessageId = latestId;
                
                container.innerHTML = '';
                
                // Afficher tous les messages de la conversation
                userMessages.forEach(msg => {
                    addAdminChatMessage(msg.message, msg.is_from_admin == 1, msg.user_name, msg.created_at);
                    
                    // Marquer comme lu
                    if (msg.is_from_admin == 0 && msg.is_read == 0) {
                        markMessageAsRead(msg.id);
                    }
                });
                
                // Auto-scroll si nouveaux messages ou déjà en bas
                if (hasNewMessages || isAtBottom) {
                    container.scrollTop = container.scrollHeight;
                } else {
                    container.scrollTop = scrollPos;
                }
                
                // Recharger la liste des utilisateurs pour mettre à jour les badges
                loadUsersList();
            })
            .catch(err => console.error('Erreur chargement conversation:', err));
    }
    
    // Fonction pour supprimer une conversation avec un utilisateur
    function deleteConversation(userId, userName) {
        // Créer une modale de confirmation personnalisée
        const modal = document.createElement('div');
        modal.id = 'delete-conversation-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(10, 16, 40, 0.92);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.25s ease;
        `;
        
        modal.innerHTML = `
            <div style="
                background: linear-gradient(135deg, rgba(24, 28, 58, 0.98), rgba(18, 22, 48, 0.98));
                padding: 2.5em;
                border-radius: 1.8em;
                box-shadow: 0 0 48px rgba(255, 45, 85, 0.6), 0 0 96px rgba(255, 45, 85, 0.3), 0 0 0 2px rgba(255, 45, 85, 0.4);
                max-width: 540px;
                text-align: center;
                border: 2px solid rgba(255, 45, 85, 0.3);
                animation: slideIn 0.3s ease;
            ">
                <div style="
                    font-size: 4em;
                    margin-bottom: 0.3em;
                    filter: drop-shadow(0 0 16px rgba(255, 45, 85, 0.8));
                ">⚠️</div>
                
                <div style="
                    font-size: 1.6em;
                    color: #ff2d55;
                    font-weight: bold;
                    margin-bottom: 0.8em;
                    letter-spacing: 0.02em;
                    text-shadow: 0 0 16px rgba(255, 45, 85, 0.5);
                ">Supprimer la conversation ?</div>
                
                <div style="
                    color: #eaf6fb;
                    font-size: 1.15em;
                    margin-bottom: 0.5em;
                    line-height: 1.5;
                ">
                    Êtes-vous sûr de vouloir supprimer toute<br>
                    la conversation avec <span style="color: #00fff9; font-weight: bold;">${userName}</span> ?
                </div>
                
                <div style="
                    color: #ff2d55;
                    font-size: 1.05em;
                    font-weight: bold;
                    margin-bottom: 2em;
                    padding: 0.8em;
                    background: rgba(255, 45, 85, 0.15);
                    border-radius: 0.8em;
                    border: 1px solid rgba(255, 45, 85, 0.3);
                ">
                    ⚡ Cette action est irréversible !
                </div>
                
                <div style="display: flex; gap: 1.2em; justify-content: center;">
                    <button id="btn-cancel-delete" class="unified-btn btn-secondary">
                        Annuler
                    </button>
                    
                    <button id="btn-confirm-delete" class="unified-btn btn-danger">
                        Supprimer
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Animations CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideIn {
                from { transform: scale(0.9) translateY(-20px); opacity: 0; }
                to { transform: scale(1) translateY(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
        
        // Gestion des boutons
        document.getElementById('btn-cancel-delete').onclick = () => {
            modal.style.animation = 'fadeOut 0.2s ease';
            setTimeout(() => modal.remove(), 200);
        };
        
        document.getElementById('btn-confirm-delete').onclick = () => {
            modal.remove();
            executeDeleteConversation(userId, userName);
        };
    }
    
    // Fonction pour exécuter la suppression
    function executeDeleteConversation(userId, userName) {
        fetch('../backend/admin_chat_messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'delete_conversation',
                user_id: userId
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Réinitialiser l'interface
                selectedUserId = null;
                selectedUserName = '';
                
                document.getElementById('chat-header').innerHTML = `
                    <div style="color: #8be9fd; font-size: 1.1em;">
                        Sélectionnez un utilisateur pour démarrer une conversation
                    </div>
                `;
                
                document.getElementById('admin-chat-messages').innerHTML = `
                    <div style="text-align: center; color: #8be9fd; padding: 2em;">
                        Aucune conversation sélectionnée
                    </div>
                `;
                
                document.getElementById('chat-input-area').style.display = 'none';
                
                // Arrêter le rafraîchissement
                if (chatRefreshInterval) {
                    clearInterval(chatRefreshInterval);
                    chatRefreshInterval = null;
                }
                
                // Recharger la liste des utilisateurs
                loadUsersList();
                
                showNotification(`Conversation avec ${userName} supprimée`, 'success');
            } else {
                alert('Erreur: ' + (data.error || 'Suppression échouée'));
            }
        })
        .catch(err => {
            console.error('Erreur suppression:', err);
            alert('Erreur réseau');
        });
    }
    
    // Fonction pour envoyer une réponse à un utilisateur
    function sendAdminReply() {
        if (!selectedUserId) {
            alert('Veuillez sélectionner un utilisateur');
            return;
        }
        
        const input = document.getElementById('admin-chat-input');
        const message = input.value.trim();
        
        if (!message) {
            alert('Veuillez saisir un message');
            return;
        }
        
        // Envoyer la réponse au backend
        fetch('../backend/admin_chat_messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'reply',
                user_id: selectedUserId,
                user_name: selectedUserName,
                message: message
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                loadConversation(selectedUserId);
                showNotification('Réponse envoyée à ' + selectedUserName, 'success');
            } else {
                alert('Erreur: ' + (data.error || 'Envoi échoué'));
            }
        })
        .catch(err => {
            console.error('Erreur envoi:', err);
            alert('Erreur réseau');
        });
    }
    
    // Ancienne fonction pour compatibilité (deprecated)
    function loadUserChatMessages() {
        fetch('../backend/admin_chat_messages.php')
            .then(r => r.json())
            .then(data => {
                console.log('Réponse du serveur:', data);
                
                if (!data.success) {
                    console.error('Erreur serveur:', data.error);
                    const container = document.getElementById('admin-chat-messages');
                    container.innerHTML = '<div style="text-align: center; color: #ff5555; padding: 2em;">Erreur: ' + (data.error || 'Impossible de charger les messages') + '</div>';
                    return;
                }
                
                if (data.success && data.messages) {
                    const container = document.getElementById('admin-chat-messages');
                    
                    if (data.messages.length === 0) {
                        container.innerHTML = '<div style="text-align: center; color: #8be9fd; padding: 2em;">Aucun message pour le moment...</div>';
                        return;
                    }
                    
                    console.log('Nombre de messages:', data.messages.length);
                    
                    // Sauvegarder la position de scroll
                    const scrollPos = container.scrollTop;
                    const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 50;
                    
                    // Vérifier s'il y a de nouveaux messages
                    const latestId = Math.max(...data.messages.map(m => m.id));
                    const hasNewMessages = latestId > lastMessageId;
                    lastMessageId = latestId;
                    
                    container.innerHTML = '';
                    
                    // Afficher TOUS les messages (déjà triés par ASC dans SQL)
                    data.messages.forEach(msg => {
                        addAdminChatMessage(msg.message, msg.is_from_admin == 1, msg.user_name, msg.created_at);
                        
                        // Marquer les messages utilisateurs comme lus
                        if (msg.is_from_admin == 0 && msg.is_read == 0) {
                            markMessageAsRead(msg.id);
                        }
                    });
                    
                    // Aller en bas si nouveaux messages ou si on était déjà en bas
                    if (hasNewMessages || isAtBottom) {
                        container.scrollTop = container.scrollHeight;
                    } else {
                        container.scrollTop = scrollPos;
                    }
                    
                    // Mettre à jour le badge
                    const unreadCount = data.messages.filter(m => m.is_from_admin == 0 && m.is_read == 0).length;
                    updateChatBadge(unreadCount);
                }
            })
            .catch(err => console.error('Erreur chargement messages:', err));
    }
    
    // Fonction pour marquer un message comme lu
    function markMessageAsRead(messageId) {
        fetch('../backend/admin_chat_messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'mark_read',
                message_id: messageId
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                console.error('Erreur marquage lu:', data.error);
            }
        })
        .catch(err => console.error('Erreur marquage:', err));
    }
    
    // Fonction pour mettre à jour le badge de chat
    function updateChatBadge(count = null) {
        const badge = document.getElementById('chat-badge');
        
        if (!badge) {
            return;
        }
        
        if (count !== null) {
            // Utiliser le count fourni
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        } else {
            // Récupérer le nombre de messages non lus
            fetch('../backend/admin_chat_messages.php')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.messages) {
                        const unreadCount = data.messages.filter(m => m.is_from_admin == 0 && m.is_read == 0).length;
                        if (unreadCount > 0) {
                            badge.textContent = unreadCount;
                            badge.style.display = 'flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(err => console.error('Erreur badge chat:', err));
        }
    }
    
    // Ne plus charger les messages au clic (déjà fait dans le DOMContentLoaded)
    
    // Mettre à jour le compteur toutes les 5 secondes
    setInterval(updateOnlineCount, 5000);

    function updateNotifBadge() {
        const badge = document.getElementById('notif-badge');
        if (!badge) return;
        
        fetch('../backend/get_notifications.php')
            .then(r => r.json())
            .then(list => {
                if(Array.isArray(list)) {
                    const unread = list.filter(n => n.is_read == 0).length;
                    if(unread > 0) {
                        badge.textContent = unread;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            });
    }
    </script>
</body>
</html>
