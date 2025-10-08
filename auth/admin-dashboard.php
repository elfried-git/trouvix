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
        }
        .admin-sidebar .logo-text {
            font-size: 2em;
            color: #a259ff;
            letter-spacing: 0.08em;
            margin-bottom: 2.5em;
        }
        .admin-sidebar a {
            width: 100%;
            margin-bottom: 1.2em;
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
            max-width: 900px;
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
            padding: 1.5em;
            box-shadow: 0 0 16px #00fff933;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            color: #eaf6fb;
            font-size: 1.1em;
        }
        .admin-table th, .admin-table td {
            padding: 0.7em 0.5em;
            text-align: center;
        }
        .admin-table thead tr {
            background: rgba(0,255,249,0.15);
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
        <!-- Sidebar -->
        <nav class="admin-sidebar">
            <div class="logo-text">TROUVIX</div>
            <a href="#" class="hote-btn">Dashboard</a>
            <a href="#" class="hote-btn">Utilisateurs</a>
            <a href="#" class="hote-btn">Contenus</a>
            <a href="#" class="hote-btn">Statistiques</a>
            <a href="#" class="hote-btn">Paramètres</a>
            <a href="../backend/logout.php" class="hote-btn quitter" style="margin-top:auto;">Déconnexion</a>
        </nav>
        <!-- Main content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <h1 style="color:#0ff1ce;font-size:2em;margin:0;">Espace Administration</h1>
                <span style="color:#eaf6fb;font-size:1.1em;">Bienvenue, Administrateur</span>
            </header>
            <!-- Stat cards -->
            <div class="admin-cards">
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
                    <div style="color:#eaf6fb;">Contenus publiés</div>
                </div>
                <div class="admin-card">
                    <div style="font-size:2.2em;font-weight:bold;color:#0ff1ce;">...</div>
                    <div style="color:#eaf6fb;">Statistiques</div>
                </div>
            </div>
            <!-- Utilisateurs connectés -->
            <section class="admin-table-section">
                <h2>Utilisateurs connectés (temps réel)</h2>
                <div class="admin-table-container">
                    <table class="admin-table" id="online-users-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Dernière connexion</th>
                            </tr>
                        </thead>
                        <tbody id="online-users-tbody">
                            <tr><td colspan="4" style="text-align:center;">Chargement...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <script>
    function fetchOnlineUsers() {
        fetch('../backend/online_users.php')
            .then(response => response.json())
            .then(users => {
                const tbody = document.getElementById('online-users-tbody');
                tbody.innerHTML = '';
                let totalOnline = 0;
                if (users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Aucun utilisateur</td></tr>';
                } else {
                    users.forEach(user => {
                        let statut = user.is_online
                            ? '<span class="online-dot"></span> <span style="color:#0f0;font-weight:bold;">En ligne</span>'
                            : '<span style="color:#aaa;">Hors ligne</span>';
                        let last = user.last_activity ? user.last_activity : '-';
                        if (user.is_online) totalOnline++;
                        tbody.innerHTML += `<tr>
                            <td>${user.nom}</td>
                            <td>${user.email}</td>
                            <td>${statut}</td>
                            <td>${user.is_online ? '-' : last}</td>
                        </tr>`;
                    });
                }
                // Statistiques dynamiques
                document.getElementById('stat-users').textContent = users.length;
                document.getElementById('stat-online').textContent = totalOnline;
            })
            .catch(() => {
                document.getElementById('online-users-tbody').innerHTML = '<tr><td colspan="4" style="text-align:center;">Erreur de chargement</td></tr>';
            });
    }
    fetchOnlineUsers();
    setInterval(fetchOnlineUsers, 1000);
    </script>
</body>
</html>
