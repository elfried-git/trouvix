// Script global pour gestion connexion/navigation Trouvix
// Placez ce fichier dans js/ et incluez-le sur toutes les pages avec une nav

function checkUserSessionAndUpdateNav() {
    fetch('backend/check_session.php', { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            const loginLink = document.getElementById('menu-login-link');
            const userIcon = document.getElementById('menu-user-icon');
            const userNom = document.getElementById('menu-user-nom');
            if (data.connected) {
                // Met à jour la dernière activité côté serveur
                fetch('backend/update_activity.php', { credentials: 'include' });
                if (userNom) userNom.textContent = data.username || 'Connecté(e)';
                if (userIcon) userIcon.style.display = 'inline-block';
                if (loginLink) loginLink.style.display = 'none';
                // Pour JS local : sessionStorage
                if (data.username) sessionStorage.setItem('user_nom', data.username);
            } else {
                if (userIcon) userIcon.style.display = 'none';
                if (loginLink) loginLink.style.display = 'inline-block';
                if (userNom) userNom.textContent = '';
                sessionStorage.removeItem('user_nom');
                if (typeof window.updateCardAccess === 'function') window.updateCardAccess();
            }
        });
}

document.addEventListener('DOMContentLoaded', () => {
    checkUserSessionAndUpdateNav();
    // Ping régulier pour garder le statut en ligne
    setInterval(() => {
        fetch('backend/update_activity.php', { credentials: 'include' });
    }, 5000); // toutes les 5 secondes

    // Ping rapide avant fermeture d'onglet/navigateur
    window.addEventListener('beforeunload', function() {
        if (navigator.sendBeacon) {
            navigator.sendBeacon('backend/update_activity.php');
        } else {
            fetch('backend/update_activity.php', { credentials: 'include', keepalive: true });
        }
    });
});

// Synchronise l'état de connexion sur tous les onglets/pages
window.addEventListener('storage', function(e) {
    if (e.key === 'user_nom') {
        checkUserSessionAndUpdateNav();
    }
});
