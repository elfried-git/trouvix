// Gestion de l'ouverture de la rubrique TV Garden
(function() {
        const tvTrigger = document.getElementById("tv-garden");
        function showAccessDeniedPopinTV() {
                if (document.getElementById('popin-access-denied-overlay')) return;
                const html = `\
                    <div id=\"popin-access-denied-overlay\">\
                        <div id=\"popin-access-denied\">\
                            <div id=\"popin-access-denied-title\">Accès TV / Replay refusé</div>\
                            <div id=\"popin-access-denied-msg\">Vous devez être connecté(e) pour accéder à la TV / Replay.</div>\
                            <button id=\"popin-access-denied-btn\">OK</button>\
                        </div>\
                    </div>`;
                document.body.insertAdjacentHTML('beforeend', html);
                document.getElementById('popin-access-denied-btn').onclick = function() {
                    const overlay = document.getElementById('popin-access-denied-overlay');
                    if (overlay) overlay.remove();
                };
                // Désactivé : la popin ne se ferme plus sur clic hors popin
        }
        function loadTv() {
                const isConnected = sessionStorage.getItem('user_nom');
                if (!isConnected) {
                        showAccessDeniedPopinTV();
                        return;
                }
                const divTv = document.createElement("div");
                divTv.id = "tv-garden-view"
                const closeBtn = document.createElement("span");
                closeBtn.textContent = "X"
                const iframe = document.createElement("iframe");
                iframe.src = "https://tv.garden";
                closeBtn.addEventListener("click", function () {
                        const existingTv = document.getElementById("tv-garden-view");
                        if (existingTv) {
                                existingTv.remove();
                        }
                });
                divTv.appendChild(iframe);
                divTv.appendChild(closeBtn);
                document.body.prepend(divTv);
        }
        if (tvTrigger) {
                tvTrigger.addEventListener("click", loadTv);
        }
})();
// Style popin accès refusé TV (si pas déjà présent)
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