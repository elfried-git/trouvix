# Langages utilisés dans ce projet

Voici les langages utilisés pour coder les différentes pages et scripts de ce projet, avec des exemples de fichiers pour chaque type :

## PHP
Utilisé pour la logique serveur, la gestion des sessions, l'authentification, la manipulation de la base de données, etc.
Exemples :
- backend/admin_login.php
- backend/add_topic.php
- pages/espace-membre.php
- hash.php
- logout.php

## JavaScript
Utilisé pour la logique côté client, l'interactivité, la communication WebSocket, etc.
Exemples :
- js/script.js
- js/admin-dashboard.js
- mots.js
- ws-server.js
- page-apprentissage-langues/apprentissage-videos.js

## HTML
Utilisé pour la structure des pages web.
Exemples :
- index.html
- admin-login.html
- pages/creer-salon.html
- forum/index.html
- page-apprentissage-langues/anglais.html

## CSS
Utilisé pour le style et la mise en page des pages web.
Exemples :
- style.css
- css/style.css
- auth/auth.css
- page-apprentissage-langues/apprentissage.css

## SQL
Utilisé pour la structure et la migration de la base de données.
Exemples :
- trouvix.sql
- backend/salons.sql
- backend/create_chat_table.sql
- backend/migrate_add_nom_hote.sql

# Trouvix

Trouvix est un site web dynamique développé principalement en PHP, JavaScript, HTML, CSS et SQL. Il propose des fonctionnalités interactives (authentification, chat, gestion de salons, notifications, etc.) et s'appuie sur une base de données pour la gestion des utilisateurs et des contenus.

Le projet est prêt pour un hébergement sur un serveur prenant en charge PHP et MySQL/MariaDB (ex : OVH, o2switch, hébergement mutualisé, VPS, XAMPP, etc.).

## Structure du projet

- `index.html` : page d'accueil (racine)
- `404.html` : page d'erreur personnalisée
- `/pages/` : pages de services (jeux, tv, actus, etc.)
- `/css/` : styles CSS
- `/js/` : scripts JavaScript
- `/assets/` : images, icônes, polices

## Hébergement

- Placez tous les fichiers à la racine de votre hébergeur.
- Les chemins sont relatifs à la racine (`/`).
- Pour GitHub Pages, placez tout dans la branche `main` ou `gh-pages`.