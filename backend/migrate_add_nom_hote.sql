-- Ajout de la colonne nom_hote pour stocker le nom de l'hôte principal
ALTER TABLE salons ADD COLUMN nom_hote VARCHAR(50) DEFAULT NULL;