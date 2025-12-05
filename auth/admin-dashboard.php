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
    <a href="notification-detail.html" class="hote-btn" id="btn-notifications" style="position:relative;">Notifications
        <span id="notif-badge" style="display:none;position:absolute;top:-10px;right:-10px;min-width:22px;height:22px;background:#ff2d55;color:#fff;border-radius:50%;font-size:1em;font-weight:bold;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(248, 0, 0, 0.2);border:2px solid #fff;z-index:2;">0</span>
    </a>
    <a href="#" class="hote-btn">Dashboard</a>
    <a href="#" class="hote-btn" id="btn-utilisateurs">Utilisateurs</a>
    <a href="#" class="hote-btn" id="forum-link" target="_blank" rel="noopener">Forum</a>
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
                    <a href="../backend/logout.php" class="hote-btn" style="
                        margin-left:1.5em;
                        max-width:220px;
                        min-width:140px;
                        text-align:center;
                        background: linear-gradient(90deg, #00fff9 0%, #a259ff 100%);
                        color: #181c3a;
                        font-size: 1.35em;
                        font-weight: 700;
                        border-radius: 1em;
                        box-shadow: 0 0 18px #00fff966;
                        padding: 0.6em 1.5em;
                        border: none;
                        text-decoration: none;
                        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
                    " onmouseover="this.style.background='linear-gradient(90deg,#a259ff 0%,#00fff9 100%)';this.style.color='#181c3a';" onmouseout="this.style.background='linear-gradient(90deg,#00fff9 0%,#a259ff 100%)';this.style.color='#181c3a';">Déconnexion</a>
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
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnUtilisateurs = document.getElementById('btn-utilisateurs');
        const btnSalons = document.getElementById('btn-salons');
        const usersSection = document.getElementById('users-section');
        const salonsSection = document.getElementById('salons-section');
        usersSection.style.display = '';
        salonsSection.style.display = 'none';
        btnUtilisateurs.addEventListener('click', function(e) {
            e.preventDefault();
            usersSection.style.display = '';
            salonsSection.style.display = 'none';
        });
        btnSalons.addEventListener('click', function(e) {
            e.preventDefault();
            salonsSection.style.display = '';
            usersSection.style.display = 'none';
            fetchSalons();
        });

        const forumLink = document.getElementById('forum-link');
        forumLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.ADMIN_SESSION && ADMIN_SESSION.nom) {
                let adminData = { nom: ADMIN_SESSION.nom };
                localStorage.setItem('ADMIN_SESSION', JSON.stringify(adminData));
            }
            window.open('http://localhost/Trouvix/forum/admin-login.html', '_blank');
        });
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
                            <td><button class="btn-supprimer-user" style="background:#e74c3c;color:#fff;border:none;padding:0.4em 0.8em;border-radius:0.4em;cursor:pointer;">Supprimer</button></td>
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
                                <button id="btn-confirmer-suppr-user" style="background:#e74c3c;color:#fff;border:none;padding:0.6em 1.5em;border-radius:0.5em;font-weight:600;font-size:1em;cursor:pointer;margin-right:1em;">Supprimer</button>
                                <button id="btn-annuler-suppr-user" style="background:#aaa;color:#181c3a;border:none;padding:0.6em 1.5em;border-radius:0.5em;font-weight:600;font-size:1em;cursor:pointer;">Annuler</button>
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

    window.addEventListener('beforeunload', function() {
        if (navigator.sendBeacon) {
            navigator.sendBeacon('../backend/logout.php');
        } else {
            fetch('../backend/logout.php', { method: 'POST', credentials: 'include', keepalive: true });
        }
    });
    </script>
    <script src="../js/admin-dashboard.js"></script>
    <script>
    function updateNotifBadge() {
        const badge = document.getElementById('notif-badge');
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
    document.addEventListener('DOMContentLoaded', function() {
        updateNotifBadge();
        setInterval(updateNotifBadge, 3000);
    });
    </script>
</body>
</html>
