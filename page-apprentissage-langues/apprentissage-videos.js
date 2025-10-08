// Script pour afficher 10 vidéos (2 lignes de 5) et un bouton retour sur chaque page langue

document.addEventListener('DOMContentLoaded', function() {
    // Cherche la section principale où injecter les vidéos
    const main = document.querySelector('main');
    if (!main) return;

    // Crée le bouton "Découvrir les vidéos" si absent
    let btn = document.getElementById('btn-videos');
    if (!btn) {
        btn = document.createElement('button');
        btn.id = 'btn-videos';
        btn.textContent = 'Découvrir les vidéos';
        btn.className = 'lw-btn';
        btn.style.margin = '2em auto 1em auto';
        btn.style.display = 'block';
        main.insertBefore(btn, main.children[1] || null);
    }

    btn.addEventListener('click', function() {
        afficherBlocVideos(main, btn);
    });
});

function afficherBlocVideos(main, btn) {
    // Supprime le bouton et les anciens blocs vidéos
    btn.remove();
    let old = document.getElementById('videos-bloc');
    if (old) old.remove();
    let oldRetour = document.getElementById('btn-retour-langues');
    if (oldRetour) oldRetour.remove();

    // Bloc vidéos
    let bloc = document.createElement('div');
    bloc.id = 'videos-bloc';
    bloc.style.margin = '2em auto';
    bloc.style.maxWidth = '1100px';
    bloc.style.textAlign = 'center';

    for (let ligne = 0; ligne < 2; ligne++) {
        let row = document.createElement('div');
        row.className = 'videos-row';
        row.style.display = 'flex';
        row.style.justifyContent = 'center';
        row.style.gap = '1em';
        row.style.marginBottom = '1em';
        for (let i = 0; i < 5; i++) {
            let video = document.createElement('video');
            video.width = 200;
            video.height = 120;
            video.controls = true;
            video.style.background = '#222';
            video.style.borderRadius = '8px';
            video.innerHTML = '<source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">Votre navigateur ne supporte pas la vidéo.';
            row.appendChild(video);
        }
        bloc.appendChild(row);
    }
    main.appendChild(bloc);
}
