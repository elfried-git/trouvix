# Guide des Styles de Boutons Uniformisés - Trouvix

## 📐 Principe de Design

Tous les boutons de l'application suivent désormais un **style unifié** avec :
- ✅ Pas d'icônes/emojis
- ✅ Bordures arrondies cohérentes (0.9em)
- ✅ Effets hover standardisés
- ✅ Transitions fluides (0.3s)
- ✅ Box-shadow subtil
- ✅ Couleurs spécifiques selon le rôle

## 🎨 Classes CSS Unifiées

### `.unified-btn` (Classe de base)
Style de base appliqué à tous les boutons :
```css
.unified-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.85em 2em;
    font-size: 1.05em;
    font-weight: 700;
    border-radius: 0.9em;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
```

### 🔵 `.btn-primary` - Actions principales
**Couleur** : Gradient Cyan/Purple (#00fff9 → #a259ff)  
**Utilisation** : Connexion, Envoyer, Valider, Confirmer

```html
<button class="unified-btn btn-primary">Envoyer</button>
```

### 🔴 `.btn-danger` - Actions destructives
**Couleur** : Gradient Rouge (#ff2d55 → #ff0055)  
**Utilisation** : Supprimer, Retirer, Détruire

```html
<button class="unified-btn btn-danger">Supprimer</button>
```

### ⚪ `.btn-secondary` - Actions secondaires
**Couleur** : Cyan transparent avec bordure (#8be9fd)  
**Utilisation** : Annuler, Retour, Fermer

```html
<button class="unified-btn btn-secondary">Annuler</button>
```

### 🟢 `.btn-success` - Actions positives
**Couleur** : Gradient Vert (#27ae60 → #2ecc71)  
**Utilisation** : Succès, Valider (contexte positif)

```html
<button class="unified-btn btn-success">Valider</button>
```

### 🔻 `.btn-danger-small` - Petite action destructive
**Couleur** : Rouge plat (#e74c3c)  
**Utilisation** : Supprimer dans les tableaux (actions compactes)

```html
<button class="unified-btn btn-danger-small">Supprimer</button>
```

## 📝 Exemples d'Utilisation

### Admin Dashboard - Chat
```html
<!-- Bouton d'envoi -->
<button id="admin-chat-send" onclick="sendAdminReply()" class="unified-btn btn-primary">
    Envoyer
</button>

<!-- Bouton de suppression de conversation -->
<button onclick="deleteConversation()" class="unified-btn btn-danger">
    Supprimer la conversation
</button>
```

### Modales de Confirmation
```html
<!-- Modal suppression utilisateur -->
<button id="btn-confirmer-suppr-user" class="unified-btn btn-danger">
    Supprimer
</button>
<button id="btn-annuler-suppr-user" class="unified-btn btn-secondary">
    Annuler
</button>
```

### Tableau Admin - Actions en ligne
```html
<td>
    <button class="btn-supprimer-user unified-btn btn-danger-small">
        Supprimer
    </button>
</td>
```

### Espace Membre - Chat
```html
<button class="chat-send-btn" id="chat-send-btn">
    Envoyer
</button>
```

## 🎯 Règles de Nommage

### ❌ ÉVITER
- Icônes dans les boutons : `🗑️ Supprimer`, `📤 Envoyer`
- Styles inline : `style="background:..."`
- Classes multiples non liées : `btn-action btn-custom`

### ✅ RECOMMANDÉ
- Texte clair sans icône : `Supprimer`, `Envoyer`
- Classes CSS : `class="unified-btn btn-danger"`
- Combinaison de classes : `unified-btn` + variante de couleur

## 🔄 Migration des Anciens Styles

### Ancien style (à remplacer)
```html
<button style="background: linear-gradient(...); color: #fff; padding: 1em 2em; ...">
    🗑️ Supprimer
</button>
```

### Nouveau style
```html
<button class="unified-btn btn-danger">
    Supprimer
</button>
```

## 📦 Fichiers Concernés

### Principaux
- ✅ `auth/admin-dashboard.php` - Boutons admin (Dashboard, Chat, Modales)
- ✅ `pages/espace-membre.php` - Boutons utilisateur (Chat, Actions)
- 🔄 `pages/salon.html` - À uniformiser
- 🔄 `pages/jeux-quiz.html` - À uniformiser
- 🔄 `pages/creer-salon.html` - À uniformiser
- 🔄 `auth/admin-login.html` - À uniformiser
- 🔄 `auth/login.html` - À uniformiser

## 🎨 Palette de Couleurs

| Rôle | Couleur Principale | Dégradé | Code Hex |
|------|-------------------|---------|----------|
| Primary | Cyan/Purple | Oui | #00fff9 → #a259ff |
| Danger | Rouge | Oui | #ff2d55 → #ff0055 |
| Secondary | Cyan | Non | #8be9fd (bordure) |
| Success | Vert | Oui | #27ae60 → #2ecc71 |
| Danger Small | Rouge plat | Non | #e74c3c |

## 🚀 Prochaines Étapes

1. ✅ **Complété** : Admin Dashboard (boutons chat, modales)
2. ✅ **Complété** : Espace Membre (chat utilisateur)
3. 🔄 **À faire** : Pages de salon (rejoindre, créer)
4. 🔄 **À faire** : Pages de jeu (quiz, devichal)
5. 🔄 **À faire** : Pages d'authentification (login, register)

## 💡 Notes Importantes

- **Accessibilité** : Les boutons restent clairs sans dépendre des emojis
- **Cohérence** : Même taille, même border-radius, même comportement hover
- **Maintenance** : Plus facile de modifier les styles CSS centralisés
- **Performance** : Moins de HTML inline, meilleure mise en cache CSS
