// Script JS pour gérer l'affichage du tableau des salons dans le dashboard admin

document.addEventListener('DOMContentLoaded', function() {
    // Sélection du bouton Utilisateurs (2e bouton du menu)
    const btnUtilisateurs = document.querySelectorAll('.hote-btn')[1];
    // Section où afficher le tableau
    let salonsSection = null;

    btnUtilisateurs.addEventListener('click', function(e) {
        e.preventDefault();
        // Si la section existe déjà, toggle l'affichage
        if (salonsSection) {
            salonsSection.style.display = salonsSection.style.display === 'none' ? '' : 'none';
            return;
        }
        // Créer la section
        salonsSection = document.createElement('section');
        salonsSection.className = 'admin-table-section';
        salonsSection.innerHTML = `
            <h2>Salons créés par les hôtes</h2>
            <div class="admin-table-container">
                <table class="admin-table" id="salons-table">
                    <thead>
                        <tr>
                            <th>Nom du salon</th>
                            <th>Code</th>
                            <th>Hôte</th>
                            <th>Max joueurs</th>
                            <th>Date de création</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                        </tr>
                    </thead>
                    <tbody id="salons-tbody">
                        <tr><td colspan="7" style="text-align:center;">Chargement...</td></tr>
                    </tbody>
                </table>
            </div>
        `;
        const cards = document.querySelector('.admin-cards');
        cards.parentNode.insertBefore(salonsSection, cards.nextSibling);
        fetchSalons();
    });
});

function fetchSalons() {
    fetch('../backend/salons.php')
        .then(response => response.json())
        .then(salons => {
            const tbody = document.getElementById('salons-tbody');
            tbody.innerHTML = '';
            if (!salons.length) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Aucun salon trouvé</td></tr>';
                return;
            }
            salons.forEach(salon => {
                let hote = '-';
                if (salon.nom_hote && salon.nom_hote.trim()) {
                    hote = salon.nom_hote;
                } else if (Array.isArray(salon.joueurs) && salon.joueurs.length > 0) {
                    const hoteObj = salon.joueurs.find(j => j.estHote) || salon.joueurs[0];
                    hote = hoteObj && hoteObj.nom && hoteObj.nom.trim() ? hoteObj.nom : 'Inconnu';
                } else {
                    hote = 'Inconnu';
                }
                const date = new Date(salon.created_at * 1000);
                tbody.innerHTML += `<tr data-id="${salon.id}">
                    <td class="salon-nom">${salon.nom}</td>
                    <td>${salon.code}</td>
                    <td>${hote}</td>
                    <td>${salon.maxJoueurs}</td>
                    <td>${date.toLocaleString('fr-FR')}</td>
                    <td><button class="btn-modifier">Modifier</button></td>
                    <td><button class="btn-supprimer" style="color:#fff;background:#e74c3c;border:none;padding:0.4em 0.8em;border-radius:0.4em;cursor:pointer;">Supprimer</button></td>
                </tr>`;
            });
            // Ajout listeners Modifier/Supprimer
            tbody.querySelectorAll('.btn-modifier').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tr = this.closest('tr');
                    const tdNom = tr.querySelector('.salon-nom');
                    const oldNom = tdNom.textContent;
                    tdNom.innerHTML = `
                        <input type="text" value="${oldNom}" 
                            style="width:96%;padding:0.6em 1em;font-size:1.1em;border-radius:0.4em;border:2px solid #0ff1ce;outline:none;box-shadow:0 0 8px #0ff1ce33;background:#10132a;color:#0ff1ce;margin-bottom:0.7em;transition:border 0.2s;">
                        <div style="margin-top:0.7em;display:flex;gap:0.7em;justify-content:center;">
                            <button class="btn-valider" style="background:#27ae60;color:#fff;border:none;padding:0.5em 1.4em;border-radius:0.4em;font-weight:600;font-size:1em;cursor:pointer;">Valider</button>
                            <button class="btn-annuler" style="background:#aaa;color:#181c3a;border:none;padding:0.5em 1.4em;border-radius:0.4em;font-weight:600;font-size:1em;cursor:pointer;">Annuler</button>
                        </div>
                    `;
                    tdNom.querySelector('.btn-valider').onclick = function() {
                        const newNom = tdNom.querySelector('input').value.trim();
                        if (!newNom) return alert('Nom invalide');
                        fetch('../backend/update_salon.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: tr.dataset.id, nom: newNom })
                        })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) {
                                tdNom.textContent = newNom;
                            } else {
                                alert(res.error || 'Erreur lors de la modification');
                                tdNom.textContent = oldNom;
                            }
                        })
                        .catch(() => {
                            alert('Erreur réseau');
                            tdNom.textContent = oldNom;
                        });
                    };
                    tdNom.querySelector('.btn-annuler').onclick = function() {
                        tdNom.textContent = oldNom;
                    };
                });
            });
            tbody.querySelectorAll('.btn-supprimer').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tr = this.closest('tr');
                    // Création de la modale personnalisée
                    let modal = document.getElementById('modal-suppr-salon');
                    if (modal) modal.remove();
                    modal = document.createElement('div');
                    modal.id = 'modal-suppr-salon';
                    modal.style = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(10,16,40,0.85);z-index:2000;display:flex;align-items:center;justify-content:center;';
                    modal.innerHTML = `
                        <div style="background:#181c3a;padding:2em 2.5em 2em 2.5em;border-radius:1.2em;box-shadow:0 0 32px #00fff966,0 0 0 2px #00fff933;text-align:center;max-width:90vw;min-width:320px;">
                            <div style="font-size:1.3em;color:#e74c3c;font-weight:bold;margin-bottom:1.2em;letter-spacing:0.02em;">Supprimer ce salon ?</div>
                            <div style="color:#eaf6fb;margin-bottom:1.5em;">Cette action est irréversible.</div>
                            <button id="btn-confirmer-suppr" style="background:#e74c3c;color:#fff;border:none;padding:0.6em 1.5em;border-radius:0.5em;font-weight:600;font-size:1em;cursor:pointer;margin-right:1em;">Supprimer</button>
                            <button id="btn-annuler-suppr" style="background:#aaa;color:#181c3a;border:none;padding:0.6em 1.5em;border-radius:0.5em;font-weight:600;font-size:1em;cursor:pointer;">Annuler</button>
                        </div>
                    `;
                    document.body.appendChild(modal);
                    document.getElementById('btn-annuler-suppr').onclick = function() {
                        modal.remove();
                    };
                    document.getElementById('btn-confirmer-suppr').onclick = function() {
                        fetch('../backend/delete_salon.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: tr.dataset.id })
                        })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) {
                                tr.remove();
                                modal.remove();
                                // Signal suppression pour tous les onglets/clients
                                localStorage.setItem('salon-supprime', Date.now());
                            } else {
                                alert(res.error || 'Erreur lors de la suppression');
                                modal.remove();
                            }
                        })
                        .catch(() => {
                            alert('Erreur réseau');
                            modal.remove();
                        });
                    };
                });
            });
        })
        .catch(() => {
            const tbody = document.getElementById('salons-tbody');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Erreur de chargement</td></tr>';
        });
}
