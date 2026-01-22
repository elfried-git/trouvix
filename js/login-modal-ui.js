(function() {
    const loginLink = document.getElementById('login-link');
    if (!loginLink) return;
    loginLink.addEventListener('click', function (e) {
        e.preventDefault();
        const nom = document.getElementById('hote-nom');
        const otp = document.getElementById('hote-otp');
        const err = document.getElementById('hote-error');
        if (nom) nom.value = '';
        if (otp) otp.value = '';
        if (err) err.textContent = '';
        document.getElementById('login-modal').setAttribute('aria-hidden', 'false');
    });
    const closeLoginBtn = document.getElementById('close-login-modal');
    if (closeLoginBtn) {
        closeLoginBtn.addEventListener('click', function () {
            const nom = document.getElementById('hote-nom');
            const otp = document.getElementById('hote-otp');
            const err = document.getElementById('hote-error');
            if (nom) nom.value = '';
            if (otp) otp.value = '';
            if (err) err.textContent = '';
            document.getElementById('login-modal').setAttribute('aria-hidden', 'true');
        });
    }
    const loginModal = document.getElementById('login-modal');
    if (loginModal) {
        loginModal.addEventListener('click', function (e) {
            if (e.target === this) {
                const nom = document.getElementById('hote-nom');
                const otp = document.getElementById('hote-otp');
                const err = document.getElementById('hote-error');
                if (nom) nom.value = '';
                if (otp) otp.value = '';
                if (err) err.textContent = '';
                this.setAttribute('aria-hidden', 'true');
            }
        });
    }
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Connexion réussie ! (simulation)');
            const nom = document.getElementById('hote-nom');
            const otp = document.getElementById('hote-otp');
            const err = document.getElementById('hote-error');
            if (nom) nom.value = '';
            if (otp) otp.value = '';
            if (err) err.textContent = '';
            document.getElementById('login-modal').setAttribute('aria-hidden', 'true');
        });
    }

    setInterval(function() {
        fetch('/trouvix/backend/update_activity.php', {
            method: 'POST',
            credentials: 'include'
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.error) {
                alert('Votre session a expiré ou a été connectée ailleurs. Vous allez être déconnecté.');
                window.location.href = '/trouvix/auth/login.html';
            }
        })
        .catch(() => {});
    }, 60000); 
})();