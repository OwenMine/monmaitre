# Guide de contribution — MON MAÎTRE

Merci de votre intérêt pour MON MAÎTRE ! Ce document décrit les conventions du projet.

---

## 🎯 Workflow Git

### Branches

- `main` → branche de production (stable)
- `dev` → branche de développement (intégration)
- `feature/xxx` → nouvelle fonctionnalité
- `fix/xxx` → correction de bug
- `hotfix/xxx` → correction urgente en production

### Convention de nommage des commits

**Types autorisés** :
- `feat` : nouvelle fonctionnalité
- `fix` : correction de bug
- `docs` : documentation
- `style` : formatage (sans changement de code)
- `refactor` : refactorisation
- `test` : tests
- `chore` : tâches diverses (build, deps…)

**Exemples** :
---

## 💻 Conventions de code

### PHP (Laravel)

- **PSR-12** obligatoire
- `php artisan pint` avant chaque commit
- Nommage :
  - Classes : `PascalCase`
  - Méthodes/variables : `camelCase`
  - Tables : `snake_case` (pluriel)
  - Colonnes : `snake_case`

### Blade

- Indentation : 4 espaces
- `@section` en haut du fichier
- Classes Tailwind inline (pas de CSS custom sauf utilitaires)

### CSS / Tailwind

- Utiliser les couleurs de la charte : `mm-navy`, `mm-secondary`, `mm-accent`
- Éviter les codes hex en dur dans les vues

### JavaScript

- Alpine.js pour l'interactivité
- Pas de jQuery
- Chart.js pour les graphiques

---

## 🧪 Tests

Toujours tester :
- ✅ Création/modification/suppression sur les modèles principaux
- ✅ Validation des formulaires (edge cases)
- ✅ Responsive (mobile, tablette, desktop)
- ✅ Les 3 espaces (admin, parent, répétiteur)

---

## 📝 Checklist avant Pull Request

- [ ] Le code compile (`npm run build`)
- [ ] Les migrations passent (`php artisan migrate`)
- [ ] Aucune erreur dans les logs (`storage/logs/laravel.log`)
- [ ] Le code suit les conventions
- [ ] Les nouveaux fichiers sont dans les bons dossiers
- [ ] Le CHANGELOG est mis à jour si nécessaire

---

## 🔐 Sécurité

- Ne **jamais** commiter le fichier `.env`
- Ne **jamais** hardcoder de mots de passe / clés API
- Toujours valider les entrées utilisateur côté serveur
- Utiliser `Gate` ou `Policy` pour les vérifications d'accès

---

Merci de contribuer à MON MAÎTRE ! 🚀