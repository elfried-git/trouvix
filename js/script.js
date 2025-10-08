
// Panel de choix de rôle pour les boutons "Jouer"
document.addEventListener('DOMContentLoaded', function() {
	const btnDeviChal = document.getElementById('btn-jouer-devichal');
	const panel = document.getElementById('panel-choix-role');
	const fermer = document.getElementById('fermer-panel-choix');
	if (btnDeviChal) {
		btnDeviChal.addEventListener('click', function(e) {
			e.preventDefault();
			panel.style.display = 'flex';
		});
	}
	if (fermer) {
		fermer.addEventListener('click', function() {
			panel.style.display = 'none';
		});
	}
	// Action sur le choix
	document.querySelectorAll('.choix-role').forEach(b => {
		b.addEventListener('click', function() {
			const role = this.getAttribute('data-role');
			if (role === 'admin') {
				window.location.href = 'admin-login.html';
				return;
			}
			if (role === 'hote') {
				window.location.href = 'hote-login.html';
				return;
			}
			alert('Rôle choisi : ' + role);
			panel.style.display = 'none';
		});
	});



});


