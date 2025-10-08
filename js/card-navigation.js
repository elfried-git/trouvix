// Navigation sur clic des cards Jeux/Quiz et Langues
// S'assure que le clic sur toute la card redirige correctement

document.addEventListener('DOMContentLoaded', function() {
    function updateCardAccess() {
        const isConnected = sessionStorage.getItem('user_nom');
        document.querySelectorAll('.service-card').forEach(function(card) {
            // Nettoie les anciens handlers
            const newCard = card.cloneNode(true);
            card.parentNode.replaceChild(newCard, card);
        });
        document.querySelectorAll('.service-card').forEach(function(card) {
            if (!isConnected) {
                card.classList.add('disabled-card');
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    showAccessDeniedPopin();
                });
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        showAccessDeniedPopin();
                    }
                });
            } else {
                card.classList.remove('disabled-card');
                card.addEventListener('click', function(e) {
                    if (card.id === 'tv-garden') return;
                    const link = card.querySelector('a.card-link');
                    if (link && link.getAttribute('href')) {
                        window.location.href = link.getAttribute('href');
                    }
                });
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        if (card.id === 'tv-garden') return;
                        const link = card.querySelector('a.card-link');
                        if (link && link.getAttribute('href')) {
                            window.location.href = link.getAttribute('href');
                        }
                    }
                });
            }
        });
    }
    updateCardAccess();
    // Rafraîchit dynamiquement l'accès aux cards sur changement de sessionStorage
    window.addEventListener('storage', function(e) {
        if (e.key === 'user_nom') {
            updateCardAccess();
        }
    });
    // Pour logout dans même onglet
    window.updateCardAccess = updateCardAccess;

// Rafraîchit la page si l'utilisateur se déconnecte dans un autre onglet ou via logout
window.addEventListener('storage', function(e) {
    if (e.key === 'user_nom' && e.oldValue && !e.newValue) {
        window.location.reload();
    }
});
});


// Style visuel pour cards désactivées et popin accès refusé
const style = document.createElement('style');
style.innerHTML = `
.disabled-card { opacity: 0.5; pointer-events: auto !important; cursor: not-allowed !important; }
#popin-access-denied-overlay {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(10,16,40,0.85); z-index: 2000; display: flex; align-items: center; justify-content: center;
}
#popin-access-denied {
    background: #181c3a; padding: 2.2em 2.5em 2em 2.5em; border-radius: 1.2em;
    box-shadow: 0 0 32px #00fff966, 0 0 0 2px #00fff933; text-align: center; max-width: 90vw;
    animation: popinIn 0.3s cubic-bezier(.4,2,.6,1) both;
}
#popin-access-denied-title {
    font-size: 1.3em; color: #00fff9; font-weight: bold; margin-bottom: 1.2em;
    letter-spacing: 0.03em; text-shadow: 0 0 8px #00fff9, 0 0 24px #00fff9;
}
#popin-access-denied-msg {
    color: #fff; font-size: 1.08em; margin-bottom: 2em;
}
#popin-access-denied-btn {
    background: linear-gradient(90deg,#00fff9 60%,#ff00ff 100%); color: #181c3a;
    border: none; border-radius: 0.7em; font-size: 1.1em; font-weight: bold;
    padding: 0.6em 2.2em; cursor: pointer; box-shadow: 0 0 12px #00fff966;
    transition: background 0.2s, color 0.2s;
}
@keyframes popinIn {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
`;
document.head.appendChild(style);

function showAccessDeniedPopin() {
        if (document.getElementById('popin-access-denied-overlay')) return;
        const html = `
        <div id="popin-access-denied-overlay">
            <div id="popin-access-denied">
                <div id="popin-access-denied-title">Accès refusé</div>
                <div id="popin-access-denied-msg">Vous devez être connecté(e) pour accéder à ce service.</div>
                <button id="popin-access-denied-btn">OK</button>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
        document.getElementById('popin-access-denied-btn').onclick = function() {
                const overlay = document.getElementById('popin-access-denied-overlay');
                if (overlay) overlay.remove();
        };
        document.getElementById('popin-access-denied-overlay').addEventListener('click', function(e) {
                if (e.target === this) this.remove();
        });
}
