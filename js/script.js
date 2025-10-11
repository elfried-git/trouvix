
document.addEventListener('DOMContentLoaded', function () {
	const btnDeviChal = document.getElementById('btn-jouer-devichal');
	const panel = document.getElementById('panel-choix-role');
	const fermer = document.getElementById('fermer-panel-choix');
	if (btnDeviChal) {
		btnDeviChal.addEventListener('click', function (e) {
			e.preventDefault();
			panel.style.display = 'flex';
		});
	}
	if (fermer) {
		fermer.addEventListener('click', function () {
			panel.style.display = 'none';
		});
	}
	document.querySelectorAll('.choix-role').forEach(b => {
		b.addEventListener('click', function () {
			const role = this.getAttribute('data-role');
			if (role === 'hote') {
				window.location.href = 'hote-login.html';
				return;
			}
			alert('Rôle choisi : ' + role);
			panel.style.display = 'none';
		});
	});



});


