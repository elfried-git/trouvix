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
    <div class="client-space-container">
        <div class="profile-card">
            <div class="profile-avatar">
                <span><?php echo strtoupper(substr($user_nom, 0, 1)); ?></span>
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
                      <input type="file" id="join-photo" accept="image/*" style="display:none;" required>
                      <span class="avatar-placeholder" id="avatar-placeholder-modal">+</span>
                      <img id="avatar-preview-modal" class="avatar-preview" src="" alt="Aperçu photo" style="display:none;">
                    </label>
                  </div>
                  <div class="form-join-btns">
                    <button type="button" class="btn-cancel-join" id="btn-cancel-join">Annuler</button>
                    <button type="submit" class="btn-go-salon">Go Salon</button>
                  </div>
                </form>
              </div>
            </div>
            <style>
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
              width: 100%;
              height: 100%;
              object-fit: cover;
              border-radius: 50%;
              box-shadow: 0 0 16px #00fff966;
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
            </style>
        </div>
        <div class="client-space-content">
            <h3>Votre espace membre</h3>
            <ul class="client-features">
                <li>Bientôt disponible ...</li>
            </ul>
        </div>
    </div>
</body>
<script>
// Ouvre la modale
const btnJoinModal = document.getElementById('btn-join-modal');
const modalJoin = document.getElementById('modal-join-salon');
btnJoinModal.onclick = function(e) {
  e.preventDefault();
  modalJoin.style.display = 'flex';
};
// Ferme la modale
const btnCancelJoin = document.getElementById('btn-cancel-join');
btnCancelJoin.onclick = function() {
  modalJoin.style.display = 'none';
};
// Vérifie le code et redirige
const formJoin = document.getElementById('form-join-salon');
formJoin.onsubmit = function(e) {
  e.preventDefault();
  const codeSaisi = document.getElementById('join-code').value.trim();
  const codeSalon = localStorage.getItem('trouvix_codeSalon');
  if (!codeSalon) {
    alert('Aucun salon n\'a été créé ou code introuvable.');
    return;
  }
  if (codeSaisi.toUpperCase() !== codeSalon.toUpperCase()) {
    alert('Code du salon incorrect.');
    return;
  }
  // On pourrait ici uploader la photo si besoin
  window.location.href = 'salon.html?code=' + encodeURIComponent(codeSalon);
};
const joinPhotoInput = document.getElementById('join-photo');
const avatarPreviewModal = document.getElementById('avatar-preview-modal');
const avatarPlaceholderModal = document.getElementById('avatar-placeholder-modal');
if (joinPhotoInput) {
  joinPhotoInput.addEventListener('change', function () {
    if (joinPhotoInput.files && joinPhotoInput.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        avatarPreviewModal.src = e.target.result;
        avatarPreviewModal.style.display = 'block';
        avatarPlaceholderModal.style.display = 'none';
      };
      reader.readAsDataURL(joinPhotoInput.files[0]);
    } else {
      avatarPreviewModal.src = '';
      avatarPreviewModal.style.display = 'none';
      avatarPlaceholderModal.style.display = 'block';
    }
  });
}
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
        padding: 1.2rem 0.5rem;
    }
    .client-space-content {
        max-width: 98vw;
    }
}
</style>
