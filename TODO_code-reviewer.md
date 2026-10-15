# TODO — Code Review : MON MAÎTRE

> **Audit produit par** : Code Reviewer Expert  
> **Date** : 2026-10-07  
> **Version du projet** : 1.0.1  
> **Repository** : https://github.com/OwenMine/monmaitre  
> **Portée** : Audit complet (Lots A → E) + correctifs appliqués

---

## 📋 Contexte

### Projet
- **Nom** : MON MAÎTRE
- **Type** : Plateforme de mise en relation parents ↔ répétiteurs à domicile (Côte d'Ivoire)
- **Stack** :
  | Composant          | Version                  |
  |--------------------|--------------------------|
  | Framework          | Laravel 13               |
  | PHP                | 8.3+                     |
  | Base de données    | MySQL (prod) / SQLite (dev) |
  | Frontend           | Blade + Alpine.js 3 + Tailwind CSS 3 |
  | Build              | Vite                     |
  | Auth               | Laravel Breeze (personnalisé) |
  | Rôles              | Spatie Laravel Permission v8 |
  | Graphiques         | Chart.js 4               |
  | Push               | WebPush + VAPID          |

### Objectif de l'audit
Produire un audit exhaustif, classé par sévérité, avec correctifs actionnables (checkboxes).

---

## 🎯 Plan de review global

| Lot | Contenu                                      | Statut          |
|-----|----------------------------------------------|-----------------|
| A   | 🔒 Sécurité critique                         | ✅ Corrigé      |
| B   | 🐛 Bugs & logique métier                     | ✅ Corrigé      |
| C   | ⚡ Performance (N+1, cache, requêtes)        | ✅ Corrigé      |
| D   | 📐 Qualité du code (modèles, services, vues) | ✅ Corrigé (C+H) + Medium/Low en cours |
| E   | 🎨 Frontend / Accessibilité                  | ⏳ À faire      |

---

## 🔍 Findings — Lot A : Sécurité

### 🔴 CRITICAL

- [x] **CR-ITEM-A.1** [Mot de passe admin hardcodé]
  - **Location** : `database/seeders/AdminSeeder.php`
  - **Impact** : Compte admin avec mot de passe public si le seeder est exécuté en production.
  - **Correctif appliqué** : Mot de passe lu depuis `env('ADMIN_DEFAULT_PASSWORD')` + exception en production si absent. Variables `ADMIN_*` ajoutées dans `.env.example`.

- [x] **CR-ITEM-A.2** [Vérification .gitignore pour .env]
  - **Correctif appliqué** : `.gitignore` créé/mis à jour. Git initialisé. `.env` non versionné. Binaires de dev (`ngrok.exe`, etc.) retirés.

- [x] **CR-ITEM-A.3** [Absence de MustVerifyEmail]
  - **Correctif appliqué** : Alternative pragmatique → marquage `email_verified_at` lors de la validation admin du profil répétiteur (pas de SMTP requis).

### 🟠 HIGH

- [x] **CR-ITEM-A.4** [Pas de vérification status à la connexion]
  - **Correctif** : Vérification `status === 'actif'` dans `LoginRequest::authenticate()` + messages explicites.

- [x] **CR-ITEM-A.5** [Middleware CheckUserStatus manquant]
  - **Correctif** : Middleware `CheckUserStatus` créé et enregistré dans `bootstrap/app.php`.

- [x] **CR-ITEM-A.6** [Autorisation côté client uniquement]
  - **Correctif** : `PlacementPolicy` créée + utilisation de `$this->authorize()`.

- [x] **CR-ITEM-A.7** [CSP trop permissif]
  - **Correctif** : `SecurityHeaders` mis à jour (CSP différencié local/prod + HSTS).

- [x] **CR-ITEM-A.8** [SESSION_SECURE_COOKIE]
  - **Correctif** : `.env.production` créé avec cookies sécurisés.

- [x] **CR-ITEM-A.9** [XSS via {!! !!}]
  - **Correctif** : Vérification effectuée → uniquement dans `vendor/pagination` (safe).

### 🟡 MEDIUM

- [x] **CR-ITEM-A.10** [Rate limiting uniquement sur user/IP]
  - **Correctif** : Double limite (user + IP) sur tous les limiters critiques.

- [x] **CR-ITEM-A.11** [Sessions non invalidées à la suspension]
  - **Correctif** : Suppression des sessions en base lors de `suspendre()`.

- [x] **CR-ITEM-A.12** [Pas de scan antivirus]
  - **Correctif** : Documentation ClamAV ajoutée dans le README (production Linux).

- [x] **CR-ITEM-A.13** [URL photos non protégée]
  - **Correctif** : Throttle `120,1` ajouté sur la route photo.

- [x] **CR-ITEM-A.14** [Nom de fichier non nettoyé]
  - **Correctif** : Nettoyage de `nom_fichier` (caractères dangereux + longueur).

### ✅ Points positifs Lot A
- Séparation des rôles Spatie bien appliquée
- Middleware `SanitizeInput` global
- Rate limiting granulaire
- Vérifications d’ownership cohérentes
- Logs d’audit admin
- Validation MIME stricte (`ValidFileType`)
- Headers de sécurité complets
- HTTPS forcé en production

---

## 🔍 Findings — Lot B : Bugs & Logique métier

### 🔴 CRITICAL

- [x] **CR-ITEM-B.1** [Doublon possible sur `Placement::genererReference()`]
  - **Correctif** : Génération basée sur la dernière référence + boucle de sécurité anti-collision.

- [x] **CR-ITEM-B.2** [Création de Placement sans vérifier `profil_valide`]
  - **Correctif** : Vérification du statut répétiteur + user actif avant création (parent + admin).

- [x] **CR-ITEM-B.3** [Race condition dans `validerParAdmin`]
  - **Correctif** : Transaction + `lockForUpdate()` + vérifications atomiques.

### 🟠 HIGH

- [x] **CR-ITEM-B.4** [Statut Demande incorrect après refus]
  - **Correctif** : Ne repasse en `recherche_repetiteur` que s’il n’y a plus de propositions en attente.

- [x] **CR-ITEM-B.5** [Matching sensible à la casse/accents]
  - **Correctif** : Méthodes `normaliser()` et `normaliserArray()` ajoutées dans `MatchingService`.

- [x] **CR-ITEM-B.6 / B.7** [Filtres matching + user actif]
  - **Correctif** : Filtre `user.status = actif` + normalisation.

- [x] **CR-ITEM-B.8** [Perte de données possible dans `ProfilController::update`]
  - **Correctif** : Transaction DB recommandée / appliquée.

- [x] **CR-ITEM-B.9** [Matière vide dans Placement]
  - **Correctif** : Fallback `'Non spécifiée'`.

### 🟡 MEDIUM / 🟢 LOW
- [x] Couleurs de statut unifiées via trait `HasStatutLabel`
- [x] Suppression de séances non `en_attente` bloquée
- [x] Divers micro-optimisations et nettoyages

### ✅ Points positifs Lot B
- Génération de référence claire (`MM-YYYY-XXXX`)
- Casts Eloquent bien utilisés
- Relations bien définies
- Labels/couleurs centralisés
- Seeders idempotents
- AuditLogger par niveau
- Notifications conditionnelles

---

## 🔍 Findings — Lot C : Performance

> Résumé des principaux points traités :
- [x] Eager loading systématique (`with()`) sur les listes admin/parent/répétiteur
- [x] Évitement des N+1 sur les scores de matching
- [x] Utilisation de `withCount` / accessors calculés une seule fois
- [x] Suppression des appels redondants à `detailsScore`
- [x] Pagination cohérente
- [x] Cache des assets (Vite)

---

## 🔍 Findings — Lot D : Qualité du code

### Critical + High (appliqués)

- [x] **D.1** Helper d’ownership
- [x] **D.2** Stats séances centralisées dans le modèle `Placement`
- [x] **D.3** Convention de nommage
- [x] **D.4** Route `profil.candidature.envoyer` cohérente
- [x] **D.5** `declare(strict_types=1)` sur les fichiers critiques
- [x] **D.6** Titres de page explicites
- [x] **D.7** Trait `HasStatutLabel` (labels + couleurs unifiés)

### Medium + Low (en cours / livrés)

- [x] **D.8** Découpage de `DemandeController::store()`
- [x] **D.9** `StoreDemandeRequest` créé
- [x] **D.10** Uniformisation des noms de routes
- [x] **D.11** Return types sur les contrôleurs principaux
- [x] **D.12** Ordre des méthodes standardisé dans les modèles
- [x] **D.13** Docblocks sur les méthodes complexes
- [x] **D.14** Nettoyage des commentaires inutiles
- [x] **D.15** `@property` / `@method` sur les modèles principaux
- [ ] **D.16** README enrichi avec captures (à finaliser)

---

## 🔍 Findings — Lot E : Frontend / Accessibilité

> À faire
- Contraste WCAG AA
- Responsive mobile (iPhone / Android / tablette)
- Navigation clavier
- Attributs ARIA
- Focus states visibles
- `alt` sur toutes les images
- Messages d’erreur accessibles

---

## 📊 Récapitulatif global

| Sévérité     | Nombre approx. | Statut          |
|--------------|----------------|-----------------|
| 🔴 Critical  | 6              | ✅ Corrigés     |
| 🟠 High      | 15+            | ✅ Corrigés     |
| 🟡 Medium    | 15+            | ✅ Majorité corrigée |
| 🟢 Low       | 10+            | ✅ Partiellement |
| ✅ Positifs  | 25+            | Conservés       |

**Total correctifs appliqués : ~54+**

---

## 🎯 Priorités restantes avant production

1. [ ] Test complet de bout en bout (3 espaces)
2. [ ] Finaliser README (captures + section sécurité)
3. [ ] Configurer SMTP (emails transactionnels)
4. [ ] Déploiement production (`.env.production`, HTTPS, MySQL, `php artisan optimize`)
5. [ ] Lot E (accessibilité + responsive)

---

## 🧪 Commandes de vérification recommandées

```bash
# Caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# Vérifications sécurité
findstr /S /I "{!!" resources\views\*.blade.php | findstr /V "vendor"
git ls-files | findstr /I "\.env"
git log --oneline

# Tests manuels prioritaires
# 1. Login compte suspendu → bloqué
# 2. Acceptation proposition → placement créé sans doublon
# 3. Matching → scores cohérents (casse/accents)
# 4. Validation / refus / suspension admin → logs d’audit OK
```

---

## 📝 Notes finales

- Le projet est **solide** et prêt pour une mise en production après le test de bout en bout.
- Les bases de sécurité, de logique métier et de qualité sont maintenant très bonnes.
- Prochaine étape recommandée : **Test complet de bout en bout** puis **préparation production**.

---

*Fichier généré le 2026-10-07 — Audit consolidé MON MAÎTRE*  
*Tous les findings Critical et High ont été traités.*
