document.addEventListener("DOMContentLoaded", main);

function main() {
    const header = document.getElementById("global-header");
    const main = document.querySelector('main');
    const tvTrigger = document.getElementById("tv-garden");

    function showAccessDeniedPopinTV() {
        if (document.getElementById('popin-access-denied-overlay')) return;

        const html = `<div id="popin-access-denied-overlay"><div id="popin-access-denied"><div id="popin-access-denied-title">Accès TV / Replay refusé</div><div id="popin-access-denied-msg">Vous devez être connecté(e) pour accéder à la TV / Replay.</div><button id="popin-access-denied-btn">OK</button></div></div>`;

        document.body.insertAdjacentHTML('beforeend', html);

        document.getElementById('popin-access-denied-btn').onclick = function () {
            const overlay = document.getElementById('popin-access-denied-overlay');
            if (overlay) overlay.remove();
        };
    }

    function closeTv(event) {
        event.stopPropagation();
        const tvContainer = document.getElementById("tv-garden-view");
        if (!tvContainer) {
            return;
        }
        tvContainer.querySelector("#tv-full-screen-btn").remove("click", () => fullScreenTv(!fullScreenState));
        tvContainer.remove("click", closeTv)
        tvContainer.querySelector("#tv-close-btn").remove("click", closeTv);
    }

    function fullScreenTv(event) {
        event.stopPropagation();
        const tvContainer = document.querySelector("#tv-garden-view");
        const tvIframe = tvContainer.querySelector("iframe");
        if (!tvIframe.classList.contains("full-screen")) {
            tvIframe.classList.add("full-screen");
            return
        }
        tvIframe.classList.remove("full-screen");
    }

    function loadTv(event) {
        event.stopPropagation();
        // Vérifier si l'utilisateur est connecté
        const isConnected = sessionStorage.getItem('user_nom');
        if (!isConnected) {
            showAccessDeniedPopinTV();
            return;
        }
        const tvContainer = document.createElement("div");
        const quickCommands = document.createElement("span");
        const tvCloseBtn = document.createElement("span");
        const tvFullScreenBtn = document.createElement("span")
        const tvIframe = document.createElement("iframe");

        tvContainer.id = "tv-garden-view";

        tvIframe.src = "https://tv.garden";

        tvCloseBtn.id = "tv-close-btn";
        tvCloseBtn.textContent = "X";
        tvCloseBtn.addEventListener("click", closeTv);

        tvFullScreenBtn.id = "tv-full-screen-btn";
        tvFullScreenBtn.textContent = "Full Screen";
        tvFullScreenBtn.addEventListener("click", fullScreenTv);

        quickCommands.id = "quick-commands";
        quickCommands.append(tvCloseBtn, tvFullScreenBtn);

        tvContainer.appendChild(quickCommands);
        tvContainer.appendChild(tvIframe);

        tvContainer.addEventListener("click", closeTv);

        document.body.prepend(tvContainer);
    }

    if (tvTrigger) {
        // N'ajouter le listener que si déjà connecté, sinon attendre le storage event
        const isConnected = sessionStorage.getItem('user_nom');
        if (isConnected) {
            tvTrigger.addEventListener("click", loadTv);
        }
        
        // Ajouter/retirer le listener dynamiquement quand connexion/déconnexion
        window.addEventListener('storage', function(e) {
            if (e.key === 'user_nom') {
                // Retirer tous les anciens listeners
                const newTrigger = tvTrigger.cloneNode(true);
                tvTrigger.parentNode.replaceChild(newTrigger, tvTrigger);
                
                // Ajouter nouveau listener si connecté
                if (e.newValue) {
                    newTrigger.addEventListener("click", loadTv);
                }
            }
        });
    }
}

if (!document.getElementById('popin-access-denied-style')) {
    const style = document.createElement('style');
    style.id = 'popin-access-denied-style';
    style.innerHTML = `
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
}