<?php
session_set_cookie_params(['path' => '/']);
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.html');
    exit;
}
$user_nom = $_SESSION['user_nom'];
$user_email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Membre - Trouvix</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <div class="header-row">
            <div class="logo" tabindex="0" aria-label="Accueil Trouvix">
                <span class="logo-text">Trouvix</span>
            </div>
            <button id="close-menu" class="close-menu" aria-label="Fermer le menu" style="display:none">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" stroke="#0ff1ce" stroke-width="3"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="8" x2="24" y2="24" />
                    <line x1="24" y1="8" x2="8" y2="24" />
                </svg>
            </button>
            <nav id="main-nav" class="main-nav" aria-label="Navigation principale">
                <ul>
                    <li><a href="../index.html">Accueil</a></li>
                    <li><a href="../index.html#contact">Contact</a></li>
                        <li id="menu-user-icon" style="display:flex;align-items:center;gap:0.4em;">
                            <a href="espace-membre.php" title="Espace membre" style="display:flex;align-items:center;gap:0.4em;">
                                <span style="font-size:1.5em;">👤</span>
                                <span id="menu-user-nom" style="font-size:1em;">
                                    <?php echo htmlspecialchars($user_nom); ?>
                                </span>
                            </a>
                        </li>
                        <!-- <li id="menu-login-link"><a href="../auth/login.html">Connexion</a></li> -->
                        <li id="menu-logout-link" style="display:none"><form action="logout.php" method="post" style="display:inline;"><button type="submit" style="background:none;border:none;color:#0ff1ce;font-size:1em;cursor:pointer;">Déconnexion</button></form></li>
                </ul>
            </nav>
        </div>
    </header>
  <script src="../js/session-nav.js"></script>
  <script>
  // Tout le JS s'exécute après que le DOM est prêt et vérifie l'existence des éléments
  document.addEventListener('DOMContentLoaded', function() {
    // Récupère la photo de l'utilisateur connecté et l'affiche dans la modale rejoindre salon
    fetch('../backend/get_user_info.php')
      .then(res => res.ok ? res.json() : null)
      .then(data => {
        var joinPhotoPreview = document.getElementById('avatar-preview-modal');
        var joinPhotoPlaceholder = document.getElementById('avatar-placeholder-modal');
        if (data && data.photo && joinPhotoPreview && joinPhotoPlaceholder) {
          joinPhotoPreview.src = data.photo;
          joinPhotoPreview.style.display = 'block';
          joinPhotoPlaceholder.style.display = 'none';
        }
      });
    // Ouvre la modale rejoindre salon
    var btnJoinModal = document.getElementById('btn-join-modal');
    var modalJoin = document.getElementById('modal-join-salon');
    if (btnJoinModal && modalJoin) {
      btnJoinModal.addEventListener('click', function(e) {
        e.preventDefault();
        modalJoin.style.display = 'flex';
      });
    }
    // Ferme la modale
    var btnCancelJoin = document.getElementById('btn-cancel-join');
    if (btnCancelJoin && modalJoin) {
      btnCancelJoin.addEventListener('click', function() {
        modalJoin.style.display = 'none';
      });
    }
    // Avatar preview pour la modale rejoindre salon
    var joinPhotoInput = document.getElementById('join-photo');
    var joinPhotoPreview = document.getElementById('avatar-preview-modal');
    var joinPhotoPlaceholder = document.getElementById('avatar-placeholder-modal');
    if (joinPhotoInput && joinPhotoPreview && joinPhotoPlaceholder) {
      joinPhotoInput.addEventListener('change', function () {
        if (joinPhotoInput.files && joinPhotoInput.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            joinPhotoPreview.src = e.target.result;
            joinPhotoPreview.style.display = 'block';
            joinPhotoPlaceholder.style.display = 'none';
          };
          reader.readAsDataURL(joinPhotoInput.files[0]);
        } else {
          joinPhotoPreview.src = '';
          joinPhotoPreview.style.display = 'none';
          joinPhotoPlaceholder.style.display = 'block';
        }
      });
    }
    // Gestion du formulaire rejoindre salon
    var formJoin = document.getElementById('form-join-salon');
    if (formJoin && joinPhotoInput) {
      formJoin.addEventListener('submit', async function(e) {
        e.preventDefault();
        // Vérifie si l'utilisateur est admin (par sécurité)
        try {
          const userInfo = await fetch('../backend/get_user_info.php');
          if (userInfo.ok) {
            const data = await userInfo.json();
            if (data && data.email && data.email === 'admin@trouvix.local') {
              showAlert("Un administrateur ne peut pas rejoindre un salon en tant que joueur. Veuillez utiliser un compte utilisateur.");
              return;
            }
          }
        } catch(e) {}
        var nom = document.getElementById('join-nom').value.trim();
        var code = document.getElementById('join-code').value.trim().toUpperCase();
        var joinPhotoPreview = document.getElementById('avatar-preview-modal');
        var photo = joinPhotoPreview && joinPhotoPreview.src ? joinPhotoPreview.src : '';
        if (!nom || !code || !photo || photo === window.location.origin + '/') {
          showAlert("Tous les champs sont obligatoires.");
          return;
        }
        // 1. Cherche le salon par code
        var salons = [];
        try {
          var res = await fetch('../backend/salons.php');
          salons = await res.json();
        } catch (err) {
          showAlert("Erreur lors de la recherche du salon.");
          return;
        }
        var salon = salons.find(function(s) { return s.code === code; });
        if (!salon) {
          showAlert("Aucun salon trouvé avec ce code. Vous ne pouvez rejoindre qu'un salon existant.");
          return;
        }
        // 2. Vérifie la place dispo
        if (!Array.isArray(salon.joueurs)) salon.joueurs = [];
        if (salon.joueurs.length >= salon.maxJoueurs && !salon.joueurs.some(function(j) { return (!j.nom || !j.photo); })) {
          showAlert("Ce salon est déjà complet.");
          return;
        }
        // 3. Vérifie que le joueur n'est pas déjà dans le salon
        if (salon.joueurs.some(function(j) { return j.nom === nom; })) {
          showAlert("Vous êtes déjà dans ce salon.");
          return;
        }
        // 4. Place intelligente : occupe la première place vide (slot sans nom ou sans photo)
        var slotTrouve = false;
        for (var i = 0; i < salon.joueurs.length; i++) {
          // Ne jamais modifier le slot 0 (hôte)
          if (i === 0) continue;
          if (!salon.joueurs[i].nom || !salon.joueurs[i].photo) {
            salon.joueurs[i] = { nom: nom, photo: photo, estHote: false };
            slotTrouve = true;
            break;
          }
        }
        // Si aucune place vide, ajoute à la fin
        if (!slotTrouve) {
          // Ajoute le joueur seulement si on ne dépasse pas maxJoueurs et jamais en index 0
          if (salon.joueurs.length < salon.maxJoueurs) {
            salon.joueurs.push({ nom: nom, photo: photo, estHote: false });
          }
        }
        // 5. Met à jour le salon côté serveur (on ne crée jamais de nouveau salon ici)
        try {
          // Refetch le salon pour garantir que le slot 0 (hôte) est strictement identique à la BDD
          var resSalonBdd = await fetch('../backend/salons.php');
          var salonsBdd = await resSalonBdd.json();
          var salonBdd = salonsBdd.find(function(s) { return s.code === code; });
          var joueursPayload = [...salon.joueurs];
          if (salonBdd && Array.isArray(salonBdd.joueurs) && salonBdd.joueurs[0]) {
            joueursPayload[0] = salonBdd.joueurs[0];
          }
          var resp = await fetch('../backend/salons.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              nom: salon.nom,
              code: salon.code,
              maxJoueurs: salon.maxJoueurs,
              longueurMot: salon.longueurMot,
              joueurs: joueursPayload
            })
          });
          if (!resp.ok) throw new Error();
        } catch (err) {
          showAlert("Erreur lors de la mise à jour du salon.");
          return;
        }
        // 6. Redirige vers la page du salon
        window.location.href = `salon.html?code=${code}`;
      });
    }
    // Affiche une alerte modale
    function showAlert(msg) {
      var modal = document.getElementById('alert-modal');
      document.getElementById('alert-message').textContent = msg;
      modal.style.display = 'flex';
      document.getElementById('alert-ok').onclick = function() {
        modal.style.display = 'none';
      };
    }
  });

  // ...existing code...
  </script>
    <div class="client-space-container">
        <div class="profile-card">
      <div class="profile-avatar">
        <?php if (!empty($_SESSION['user_photo'])): ?>
          <img src="<?php echo htmlspecialchars($_SESSION['user_photo']); ?>" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
        <?php else: ?>
          <span><?php echo strtoupper(substr($user_nom, 0, 1)); ?></span>
        <?php endif; ?>
      </div>
            <div class="profile-info">
                <h2>Bienvenue, <span class="profile-name"><?php echo htmlspecialchars($user_nom); ?></span> !</h2>
                <div class="profile-email">Email : <span><?php echo htmlspecialchars($user_email); ?></span></div>
            </div>
            <form action="logout.php" method="post" class="logout-form">
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
            <button class="btn-main btn-join-salon" id="btn-join-modal">Rejoindre un salon</button>
            <div id="modal-join-salon" class="modal-bg" style="display:none;">
              <div class="modal-box">
                <h2 style="color:#00fff9;text-align:center;margin-bottom:1em;">Rejoindre un salon</h2>
                <form id="form-join-salon" autocomplete="off">
                  <div class="form-group">
                    <label for="join-nom" style="color:#fff;font-weight:600;">Nom du joueur</label>
                    <input type="text" id="join-nom" class="form-input" value="<?php echo htmlspecialchars($user_nom); ?>" readonly style="background:#222;color:#00fff9;">
                  </div>
                  <div class="form-group">
                    <label for="join-code" style="color:#fff;font-weight:600;">Code du salon</label>
                    <input type="text" id="join-code" class="form-input" maxlength="10" required placeholder="Ex: VIX-1234">
                  </div>
                  <div class="form-group">
                    <label for="join-photo" style="color:#fff;font-weight:600;">Photo</label>
                    <label class="avatar-upload-label">
                      <input type="file" id="join-photo" accept="image/*" style="display:none;" disabled>
                      <span class="avatar-placeholder" id="avatar-placeholder-modal" style="display:none;">+</span>
                      <img id="avatar-preview-modal" class="avatar-preview" src="" alt="Aperçu photo" style="display:none;">
                    </label>
                  </div>
                  <div class="form-join-btns">
                    <button type="button" class="btn-cancel-join" id="btn-cancel-join">Annuler</button>
                    <button type="submit" class="btn-go-salon">Rejoindre hôte</button>
                  </div>
                </form>
              </div>
            </div>
            <div id="alert-modal" class="modal-bg" style="display:none;">
              <div class="modal-box" style="max-width:340px;">
                <div id="alert-message" style="color:#fff;font-size:1.08em;margin-bottom:1.7em;text-align:left;"></div>
                <button id="alert-ok" class="btn-main" style="width:120px;margin:0 auto;display:block;background:linear-gradient(90deg,#00fff9 0%,#ff00ff 100%);color:#181c3a;">OK</button>
              </div>
            </div>
            <style>
/* Harmonisation avatars salon membre */
.avatar {
  width: 74px;
  height: 74px;
  border-radius: 50%;
  border: 3px solid #00fff9;
  background: linear-gradient(135deg, #181c3a 60%, #00fff9 100%);
  object-fit: cover;
  box-shadow: 0 0 16px #00fff966, 0 0 32px #00fff933;
  transition: box-shadow 0.22s, border 0.22s;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
.avatar.player {
  border: 3px solid #ffe600;
  background: linear-gradient(135deg, #181c3a 60%, #ffe600 100%);
  box-shadow: 0 0 24px #ffe60099, 0 0 48px #00fff966;
}
.avatar.player:hover {
  box-shadow: 0 0 32px #ffe600cc, 0 0 64px #00fff9cc;
  border: 3px solid #fff200;
}
.player-block {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 2em;
}
.host {
  border: 3px solid #ff00ff;
  box-shadow: 0 0 24px #ff00ff99, 0 0 48px #00fff966;
}
.player-name {
  color: #00fff9;
  font-weight: 600;
  font-size: 1.08em;
  text-align: center;
  margin-top: 0.2em;
  text-shadow: 0 0 8px #00fff9cc;
}
            .btn-main {
                width: 100%;
                max-width: 320px;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            .btn-join-salon {
                margin-top: 1em;
                background: linear-gradient(90deg, #00fff9 0%, #ff00ff 100%);
                color: #181c3a;
                font-weight: bold;
                border: none;
                border-radius: 0.9em;
                font-size: 1.18em;
                padding: 0.95em 2.4em;
                box-shadow: 0 0 24px #00fff9cc, 0 0 48px #ff00ff66;
                letter-spacing: 0.04em;
                text-shadow: 0 0 8px #fff, 0 0 16px #00fff9cc;
                transition: background 0.22s, color 0.22s, box-shadow 0.22s, transform 0.13s;
                position: relative;
                overflow: hidden;
                outline: none;
                pointer-events: auto !important;
                cursor: pointer;
            }
            .btn-join-salon:hover, .btn-join-salon:focus {
                background: linear-gradient(90deg, #ff00ff 0%, #00fff9 100%);
                color: #fff;
                box-shadow: 0 0 40px #ff00ffaa, 0 0 0 2px #00fff933;
                transform: scale(1.06);
            }
            .modal-bg {
              position: fixed;top: 0;left: 0;width: 100vw;height: 100vh;
              background: rgba(10,16,40,0.92);z-index: 1000;display: flex;align-items: center;justify-content: center;
            }
            .modal-box {
              background: #181c3a;padding: 2.2em 2.5em 2em 2.5em;border-radius: 1.2em;
              box-shadow: 0 0 32px #00fff966,0 0 0 2px #00fff933, 0 0 80px 8px #ff00ff22;
              text-align: center;max-width: 90vw;border: 1.5px solid #00fff9;
              animation: fadeIn 0.7s cubic-bezier(.4,0,.2,1);
            }
            .form-group {margin-bottom: 1.7em;text-align: left;display: flex;flex-direction: column;gap: 0.45em;}
            .form-input {width: 85%;min-width: 160px;max-width: 260px;margin-left: auto;margin-right: auto;display: block;background: rgba(24,28,58,0.92);color: #00fff9;border: 2px solid #00fff9;border-radius: 0.7em;padding: 0.85em 1.1em 0.85em 1.1em;font-size: 1.13em;outline: none;margin-top: 0;margin-bottom: 0;transition: border 0.22s, box-shadow 0.22s;box-shadow: 0 0 0 0 #00fff9;font-weight: 500;line-height: 1.25;}
            .form-input:focus {border: 2.5px solid #ff00ff;box-shadow: 0 0 16px #ff00ff66, 0 0 24px #00fff9aa;background: #181c3a;color: #fff;}
            .avatar-upload-label {
              width: 84px;
              height: 84px;
              border-radius: 50%;
              border: 3px solid #00fff9;
              display: flex;
              align-items: center;
              justify-content: center;
              background: #222;
              box-shadow: 0 0 16px #00fff966, 0 0 32px #00fff933;
              margin: 0.2em auto 0.7em auto;
              transition: border 0.22s, box-shadow 0.22s;
              cursor: pointer;
              overflow: hidden;
            }
            .avatar-upload-label:hover, .avatar-upload-label:focus {
              border: 3px solid #ff00ff;
              box-shadow: 0 0 24px #ff00ff99, 0 0 48px #00fff966;
            }
            .avatar-placeholder {
              color: #00fff9;
              font-size: 2.5em;
              opacity: 0.7;
              transition: color 0.2s;
            }
            .avatar-upload-label:hover .avatar-placeholder {
              color: #ff00ff;
              opacity: 0.9;
            }
            .avatar-preview {
              display: none;
              width: 84px;
              height: 84px;
              object-fit: cover;
              border-radius: 50%;
              box-shadow: 0 0 24px #00fff9cc, 0 0 48px #ff00ff66;
              border: 3px solid #00fff9;
              animation: avatarGlow 2.2s infinite alternate;
              transition: box-shadow 0.22s, transform 0.18s;
            }
            @keyframes avatarGlow {
              0% { box-shadow: 0 0 24px #00fff9cc, 0 0 48px #ff00ff66; }
              100% { box-shadow: 0 0 48px #ff00ffcc, 0 0 80px #00fff9cc; }
            }
            .avatar-preview:hover {
              transform: scale(1.08) rotate(-6deg);
              box-shadow: 0 0 64px #ff00ffcc, 0 0 96px #00fff9cc;
              filter: brightness(1.15) saturate(1.3);
            }
            #form-join-salon {
              display: flex;
              flex-direction: column;
            }
            .form-join-btns {
              display: flex;
              justify-content: space-between;
              gap: 1.2em;
              margin-top: 2em;
            }
            .btn-cancel-join {
              flex: 1;
              background: linear-gradient(90deg,#222 60%,#00fff9 100%);
              color: #fff;
              border: none;
              border-radius: 0.9em;
              font-size: 1.13em;
              font-weight: bold;
              padding: 0.85em 2.2em;
              cursor: pointer;
              box-shadow: 0 0 18px #00fff966, 0 0 0 2px #00fff933;
              transition: background 0.22s, color 0.22s, box-shadow 0.22s, transform 0.13s;
              letter-spacing: 0.04em;
              text-shadow: 0 0 8px #00fff9cc;
              outline: none;
            }
            .btn-cancel-join:hover, .btn-cancel-join:focus {
              background: linear-gradient(90deg, #00fff9 0%, #222 100%);
              color: #ff00ff;
              box-shadow: 0 0 32px #00fff9cc;
              transform: scale(1.04);
            }
            .btn-go-salon {
              flex: 1;
              background: linear-gradient(90deg, #ff00ff 0%, #00fff9 100%);
              color: #181c3a;
              border: none;
              border-radius: 0.9em;
              font-size: 1.13em;
              font-weight: bold;
              padding: 0.85em 2.2em;
              cursor: pointer;
              box-shadow: 0 0 24px #ff00ff66, 0 0 0 2px #00fff933;
              transition: background 0.22s, color 0.22s, box-shadow 0.22s, transform 0.13s;
              letter-spacing: 0.04em;
              text-shadow: 0 0 8px #fff;
              outline: none;
            }
            .btn-go-salon:hover, .btn-go-salon:focus {
              background: linear-gradient(90deg, #00fff9 0%, #ff00ff 100%);
              color: #fff;
              box-shadow: 0 0 40px #ff00ffaa, 0 0 0 2px #00fff933;
              transform: scale(1.06);
            }
            .form-join-btns button {
              min-width: 100px;
              max-width: 140px;
              width: auto;
              padding: 0.5em 1.1em;
              font-size: 0.98em;
              border-radius: 0.7em;
            }
            @media (max-width: 600px) {
              .modal-box {
                min-width: 90vw;
                max-width: 98vw;
                padding: 2.2em 0.5em 2em 0.5em;
                border-radius: 1.1em;
              }
              .form-input {
                font-size: 1.08em;
                padding: 0.7em 0.7em;
                min-width: 0;
                max-width: 98vw;
              }
              .avatar-upload-label {
                width: 64px;
                height: 64px;
              }
              .form-join-btns button {
                min-width: 90px;
                max-width: 120px;
                font-size: 0.95em;
                padding: 0.5em 0.7em;
              }
            }
            @media (max-width: 900px) {
              .modal-box {
                min-width: 92vw;
                max-width: 99vw;
                padding: 2.2em 1em 2em 1em;
                border-radius: 1.1em;
              }
              .form-input {
                font-size: 1.13em;
                padding: 0.9em 1em;
                min-width: 0;
                max-width: 99vw;
              }
              .avatar-upload-label {
                width: 74px;
                height: 74px;
              }
              .form-join-btns button {
                min-width: 110px;
                max-width: 180px;
                font-size: 1.05em;
                padding: 0.7em 1.2em;
              }
            }
            @media (max-width: 430px) {
              .modal-box {
                min-width: 98vw;
                max-width: 100vw;
                padding: 1.2em 0.2em 1.2em 0.2em;
                border-radius: 0.7em;
              }
              .form-input {
                font-size: 1em;
                padding: 0.7em 0.5em;
                min-width: 0;
                max-width: 100vw;
              }
              .avatar-upload-label {
                width: 54px;
                height: 54px;
              }
              .form-join-btns button {
                min-width: 80px;
                max-width: 120px;
                font-size: 0.92em;
                padding: 0.5em 0.7em;
              }
            }
            @media (max-width: 412px) {
              .modal-box {
                min-width: 99vw;
                max-width: 100vw;
                padding: 1em 0.1em 1em 0.1em;
                border-radius: 0.5em;
              }
              .form-input {
                font-size: 0.98em;
                padding: 0.6em 0.3em;
                min-width: 0;
                max-width: 100vw;
              }
              .avatar-upload-label {
                width: 48px;
                height: 48px;
              }
              .form-join-btns button {
                min-width: 70px;
                max-width: 100px;
                font-size: 0.88em;
                padding: 0.4em 0.5em;
              }
            }
            @media (max-width: 390px) {
              .modal-box {
                min-width: 100vw;
                max-width: 100vw;
                padding: 0.7em 0.05em 0.7em 0.05em;
                border-radius: 0.4em;
              }
              .form-input {
                font-size: 0.95em;
                padding: 0.5em 0.2em;
                min-width: 0;
                max-width: 100vw;
              }
              .avatar-upload-label {
                width: 40px;
                height: 40px;
              }
              .form-join-btns button {
                min-width: 60px;
                max-width: 90px;
                font-size: 0.85em;
                padding: 0.3em 0.4em;
              }
            }
            .modal-bg {
              z-index: 2000;
            }
            </style>
        </div>
        <div class="client-space-content">
      <div id="member-salon-block" style="width:100%;max-width:420px;margin:0 auto 2em auto;padding:2em 1em 1.5em 1em;background:rgba(24,28,58,0.98);border-radius:1.7rem;box-shadow:0 0 48px #00fff9cc,0 0 0 3px #00fff933,0 0 80px 8px #ff00ff22;border:1.5px solid #00fff9;">
                <div id="member-salon-message" style="text-align:center;font-size:1.18em;font-weight:bold;margin-bottom:1.1em;color:#00fff9;text-shadow:0 0 8px #00fff9cc;"></div>
                <h2 style="color:#00fff9;text-align:center;margin-bottom:0.7em;">Salon</h2>
                <div id="member-salon-code" style="text-align:center;font-size:1.12em;font-weight:600;color:#ff00ff;margin-bottom:1.1em;text-shadow:0 0 8px #ff00ff99;"></div>
                <div id="member-host-info" style="text-align:center;margin-bottom:2em;"></div>
                <div class="players" id="member-players-list"></div>
                <button id="btn-betou-kouenda" style="display:block;margin:2em auto 0 auto;padding:0.9em 2.2em;font-size:1.18em;font-weight:bold;border-radius:1em;background:linear-gradient(90deg,#00fff9 0%,#ff00ff 100%);color:#181c3a;box-shadow:0 0 16px #00fff9cc,0 0 32px #ff00ff99;border:none;cursor:pointer;transition:background 0.2s,box-shadow 0.2s;">Betou Kouenda</button>
        <div id="member-salon-message" style="text-align:center;font-size:1.18em;font-weight:bold;margin-bottom:1.1em;color:#00fff9;text-shadow:0 0 8px #00fff9cc;"></div>
        <div id="member-salon-code" style="text-align:center;font-size:1.12em;font-weight:600;color:#ff00ff;margin-bottom:1.1em;text-shadow:0 0 8px #ff00ff99;"></div>
        <div id="member-host-info" style="text-align:center;margin-bottom:2em;"></div>
        <div class="players" id="member-players-list"></div>
      </div>
        </div>
    </div>
</body>
<script>
// --- Affichage du salon rejoint dans l'espace membre ---
async function fetchSalonForUser() {
  try {
    const userInfoRes = await fetch('../backend/get_user_info.php');
    if (!userInfoRes.ok) return null;
    const userInfo = await userInfoRes.json();
    if (!userInfo || !userInfo.nom) return null;
    const salonsRes = await fetch('../backend/salons.php');
    if (!salonsRes.ok) return null;
    const salons = await salonsRes.json();
    // Cherche le salon où l'utilisateur est présent
    for (const salon of salons) {
      if (Array.isArray(salon.joueurs) && salon.joueurs.some(j => j && j.nom === userInfo.nom)) {
        return { salon, userNom: userInfo.nom };
      }
    }
    return null;
  } catch (e) { return null; }
}

async function renderMemberSalonBlock() {
  const block = document.getElementById('member-salon-block');
  if (!block) return;
  const data = await fetchSalonForUser();
  if (!data) {
    block.style.display = 'none';
    return;
  }
  const { salon, userNom } = data;
  // Si le joueur n'est plus dans la liste, masquer le bloc
  if (!salon.joueurs.some(j => j && j.nom === userNom)) {
    block.style.display = 'none';
    return;
  }
  // Afficher ou masquer le bouton Betou Kouenda selon si l'utilisateur est l'hôte
  const btnBetou = document.getElementById('btn-betou-kouenda');
  if (btnBetou) {
    // Affiche le bouton pour tous les joueurs présents dans le salon
    if (salon.joueurs.some(j => j && j.nom === userNom)) {
      btnBetou.style.display = 'block';
      btnBetou.onclick = function() {
        if (salon.code) {
          localStorage.setItem('codeSalon', salon.code);
          sessionStorage.setItem('codeSalon', salon.code);
          window.location.href = 'jeux-encours.html?code=' + encodeURIComponent(salon.code);
        } else {
          window.location.href = 'jeux-encours.html';
        }
      };
    } else {
      btnBetou.style.display = 'none';
    }
  }
  document.getElementById('member-salon-code').textContent = `Code du salon : ${salon.code}`;
  // Hôte
  const hote = salon.joueurs[0];
  let nomAffiche = hote && hote.nom ? hote.nom : 'Hôte';
  document.getElementById('member-host-info').innerHTML = `
    <div class="player-block">
      <img src="${hote && hote.photo ? hote.photo : '../assets/avatar-default.png'}" class="avatar host" alt="Avatar Hôte">
      <div class="player-name">${nomAffiche} <span style="font-size:0.8em;color:#ff00ff;">(Hôte)</span></div>
    </div>
  `;
  // Joueurs (hors hôte)
  const playersList = document.getElementById('member-players-list');
  playersList.innerHTML = '';
  const totalSlots = salon.maxJoueurs;
  for (let i = 1; i < totalSlots; i++) {
    const joueur = salon.joueurs[i];
    if (joueur && joueur.nom && joueur.photo) {
      let avatarAttrs = `src="${joueur.photo}" class="avatar player" alt="Avatar joueur"`;
      // Si l'utilisateur connecté est l'hôte, rendre la photo cliquable
      if (userNom === (hote && hote.nom)) {
        avatarAttrs += ` data-nom="${joueur.nom}" style="cursor:pointer;box-shadow:0 0 32px #ffe600cc,0 0 64px #00fff9cc;transition:box-shadow 0.18s;"`;
      }
      playersList.innerHTML += `
        <div class="player-block">
          <img ${avatarAttrs} />
          <div class="player-name">${joueur.nom}${joueur.nom === userNom ? ' <span style=\"color:#ffe600;font-size:0.9em;\">(Vous)</span>' : ''}</div>
        </div>
      `;
    } else {
      playersList.innerHTML += `
        <div class="player-slot">
          <div class="photo-placeholder" style="width:70px;height:70px;border-radius:50%;background:rgba(0,255,249,0.13);border:2.5px solid #00fff9;box-shadow:0 0 16px #00fff966,0 0 32px #ff00ff99;margin:0 auto 0.5em auto;"></div>
          <div class="slot-status">Disponible</div>
        </div>
      `;
    }
  }
  // Popin pour retirer un joueur (hôte uniquement)
  if (userNom === (hote && hote.nom)) {
    setTimeout(() => {
      document.querySelectorAll('.avatar.player[data-nom]').forEach(img => {
        img.onclick = function(e) {
          const nomARetirer = this.getAttribute('data-nom');
          showKickPopin(nomARetirer, salon.code, () => renderMemberSalonBlock());
        };
      });
    }, 100);
  }
// Popin stylée pour retirer un joueur
function showKickPopin(nom, code, onKick) {
  // Supprime toute popin existante
  let old = document.getElementById('kick-popin');
  if (old) old.remove();
  const popin = document.createElement('div');
  popin.id = 'kick-popin';
  popin.innerHTML = `
    <div style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(10,16,40,0.92);z-index:3000;display:flex;align-items:center;justify-content:center;">
      <div style="background:#181c3a;padding:2.2em 2.5em 2em 2.5em;border-radius:1.2em;box-shadow:0 0 32px #00fff966,0 0 0 2px #00fff933,0 0 80px 8px #ff00ff22;text-align:center;max-width:90vw;border:1.5px solid #00fff9;">
        <div style='font-size:1.18em;color:#ff0055;font-weight:bold;margin-bottom:1.2em;'>Retirer <span style="color:#ffe600;">${nom}</span> du salon ?</div>
        <button id="btn-kick-confirm" style="background:linear-gradient(90deg,#ff0055 0%,#ffe600 100%);color:#181c3a;font-weight:bold;font-size:1.13em;padding:0.7em 2.2em;border-radius:0.8em;border:none;box-shadow:0 0 16px #ff0055cc,0 0 32px #ffe60099;cursor:pointer;">Retirer</button>
        <button id="btn-kick-cancel" style="margin-left:1.2em;background:linear-gradient(90deg,#222 60%,#00fff9 100%);color:#fff;font-weight:bold;font-size:1.13em;padding:0.7em 2.2em;border-radius:0.8em;border:none;box-shadow:0 0 16px #00fff966,0 0 32px #00fff933;cursor:pointer;">Annuler</button>
      </div>
    </div>
  `;
  document.body.appendChild(popin);
  document.getElementById('btn-kick-cancel').onclick = () => popin.remove();
  document.getElementById('btn-kick-confirm').onclick = async function() {
    // Appel API pour retirer le joueur
    try {
      const res = await fetch('../backend/kick_player.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code, nom })
      });
      const result = await res.json();
      popin.remove();
      if (result && result.success) {
        if (typeof onKick === 'function') onKick();
      } else {
        alert(result && result.error ? result.error : 'Erreur lors du retrait');
      }
    } catch (e) {
      popin.remove();
      alert('Erreur réseau lors du retrait');
    }
  };
}
  // Message dynamique
  const joueursConnectes = salon.joueurs.filter(j => j && j.nom && j.photo).length;
  const salonMsg = document.getElementById('member-salon-message');
  if (joueursConnectes < totalSlots) {
    salonMsg.textContent = 'En attente des joueurs...';
    salonMsg.style.color = '#00fff9';
  } else {
    salonMsg.textContent = 'Place au jeu !';
    salonMsg.style.color = '#ff00ff';
  }
}
renderMemberSalonBlock();
</script>
</html>
<style>

.client-space-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    background: var(--bg-gradient, linear-gradient(135deg, #0a0a23 0%, #1a2236 100%));
    padding-top: 4vh;
}
.profile-card {
    background: var(--card-bg, rgba(20,30,60,0.95));
    box-shadow: var(--shadow, 0 8px 32px 0 rgba(0,255,255,0.12));
    border-radius: 1.5rem;
    padding: 2.5rem 2.5rem 2rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2.5rem;
    min-width: 320px;
    max-width: 95vw;
    position: relative;
}
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ff1ce 60%, #a259ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.7rem;
    color: #181c3a;
    font-weight: bold;
    margin-bottom: 1.2rem;
    box-shadow: 0 0 18px #0ff1ce55;
}
.profile-info {
    text-align: center;
    margin-bottom: 1.2rem;
}
.profile-name {
    color: #0ff1ce;
    font-weight: 700;
}
.profile-email {
    color: #8be9fd;
    font-size: 1.08em;
    margin-top: 0.2em;
}
.logout-form {
    margin-top: 1.2rem;
}
.logout-btn {
    background: linear-gradient(90deg, #00ffe7 60%, #a259ff 100%);
    color: #181c3a;
    border: none;
    border-radius: 0.7rem;
    padding: 0.9rem 2.2rem;
    font-size: 1.13rem;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 0 16px #00ffe766;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.logout-btn:hover, .logout-btn:focus {
    background: linear-gradient(90deg, #a259ff 60%, #00ffe7 100%);
    color: #fff;
    box-shadow: 0 0 24px #a259ff99;
}
.client-space-content {
    background: var(--card-bg, rgba(20,30,60,0.95));
    box-shadow: var(--shadow, 0 8px 32px 0 rgba(0,255,255,0.12));
    border-radius: 1.3rem;
    padding: 2.2rem 2rem 2rem 2rem;
    max-width: 420px;
    width: 100%;
    text-align: center;
}
.client-space-content h3 {
    color: #0ff1ce;
    margin-bottom: 1.2rem;
    font-size: 1.35em;
}
.client-features {
    list-style: none;
    padding: 0;
    margin: 0;
    color: #eaf6fb;
    font-size: 1.08em;
}
.client-features li {
    margin-bottom: 0.8em;
    padding-left: 1.2em;
    position: relative;
    text-align: left;
}
.client-features li:before {
    content: '\2714';
    color: #0ff1ce;
    position: absolute;
    left: 0;
    font-size: 1.1em;
    top: 0.1em;
}
@media (max-width: 600px) {
  .profile-card, .client-space-content {
    min-width: unset;
    padding: 2.5rem 0.5rem;
  }
  .client-space-content {
    max-width: 100vw !important;
    width: 100vw !important;
    min-width: 0 !important;
    border-radius: 2em !important;
    font-size: 1.5em !important;
  }
}
</style>
