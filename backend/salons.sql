-- SQL pour la table salons (à exécuter dans phpMyAdmin ou autre)
CREATE TABLE IF NOT EXISTS salons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    code VARCHAR(10) NOT NULL,
    maxJoueurs INT NOT NULL,
    longueurMot INT NOT NULL,
    joueurs TEXT NOT NULL, -- JSON
    created_at INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;