// Récupère les infos de session de l'admin (nom/email) pour le JS du dashboard
fetch('../backend/get_user_info.php')
  .then(res => res.ok ? res.json() : null)
  .then(data => {
    if (data && data.nom && data.email) {
      window.ADMIN_SESSION = { nom: data.nom, email: data.email };
    }
  });
