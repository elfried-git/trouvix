
let activityPingInterval = null;
function checkUserSessionAndUpdateNav() {
    fetch('/Trouvix/backend/check_session.php', { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            const loginLink = document.getElementById('menu-login-link');
            const userIcon = document.getElementById('menu-user-icon');
            const userNom = document.getElementById('menu-user-nom');
            if (data.connected) {
                fetch('/Trouvix/backend/update_activity.php', { credentials: 'include' });
                if (userNom) userNom.textContent = data.username || 'Connecté(e)';
                if (userIcon) userIcon.style.display = 'inline-block';
                if (loginLink) loginLink.style.display = 'none';
                if (data.username) sessionStorage.setItem('user_nom', data.username);
                if (!activityPingInterval) {
                    activityPingInterval = setInterval(() => {
                        fetch('/Trouvix/backend/update_activity.php', { credentials: 'include' });
                    }, 5000);
                }
            } else {
                if (userIcon) userIcon.style.display = 'none';
                if (loginLink) loginLink.style.display = 'inline-block';
                if (userNom) userNom.textContent = '';
                sessionStorage.removeItem('user_nom');
                if (typeof window.updateCardAccess === 'function') window.updateCardAccess();
                if (activityPingInterval) {
                    clearInterval(activityPingInterval);
                    activityPingInterval = null;
                }
            }
        })
        .catch(err => {
            console.error('Erreur lors de la vérification de session:', err);
        });
}

document.addEventListener('DOMContentLoaded', () => {
    checkUserSessionAndUpdateNav();
    const burger = document.getElementById('burger-menu');
    const closeMenu = document.getElementById('close-menu');
    const mainNav = document.getElementById('main-nav');
    if (burger && closeMenu && mainNav) {
        burger.addEventListener('click', (e) => {
            e.stopPropagation();
            mainNav.classList.add('open');
            closeMenu.style.display = 'block';
        });
        closeMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            mainNav.classList.remove('open');
            closeMenu.style.display = 'none';
        });
        mainNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mainNav.classList.remove('open');
                closeMenu.style.display = 'none';
            });
        });
        document.addEventListener('click', (e) => {
            if (mainNav.classList.contains('open') && !mainNav.contains(e.target) && e.target !== burger) {
                mainNav.classList.remove('open');
                closeMenu.style.display = 'none';
            }
        });
        mainNav.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }


    window.addEventListener('beforeunload', function() {
        if (activityPingInterval) {
            clearInterval(activityPingInterval);
            activityPingInterval = null;
        }
        if (navigator.sendBeacon) {
            navigator.sendBeacon('/Trouvix/backend/update_activity.php');
        } else {
            fetch('/Trouvix/backend/update_activity.php', { credentials: 'include', keepalive: true });
        }
    });
});

window.addEventListener('storage', function(e) {
    if (e.key === 'user_nom') {
        checkUserSessionAndUpdateNav();
    }
});
