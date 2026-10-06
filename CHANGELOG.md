# Changelog — MON MAÎTRE

Toutes les modifications notables de ce projet sont documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/)
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

---

## [Non publié]

### À venir

- Emails transactionnels (SMTP)
- Tableau de bord répétiteur enrichi (revenus, statistiques)
- Système de paiement en ligne
- Amélioration responsive mobile
- Tests unitaires (PHPUnit)

---

## [1.0.0] - 2026-09-25

### 🎉 Version initiale

Première version stable de MON MAÎTRE — plateforme de placement et de suivi de répétiteurs à domicile en Côte d'Ivoire.

---

### ✨ Ajouté

#### 🎨 Design & Interface

- **Charte graphique complète** MON MAÎTRE avec palette officielle :
    - Bleu principal `#0B3D91`
    - Bleu marine `#062B5C`
    - Bleu secondaire `#143B8F`
    - Or / Doré `#F5A623`
    - Anthracite `#1E1E1E`
    - Gris clair `#F5F6FA`
- **Logos intégrés** : version complète (header public, login) + icône seule (3 espaces internes)
- **Refonte complète** des 5 layouts :
    - Public (`layouts/app.blade.php`)
    - Admin (`layouts/admin.blade.php`)
    - Parent (`layouts/parent.blade.php`)
    - Répétiteur (`layouts/repetiteur.blade.php`)
    - Login (`auth/login.blade.php`)
- **Différenciation visuelle** entre les 3 espaces (admin = header navy, parent = accents navy, répétiteur = accents bleu vif)

#### 🔐 Authentification

- **Login unifié intelligent** : accepte un **email OU un téléphone** dans un seul champ
- **Normalisation automatique** des numéros ivoiriens (`07 19 16 76 88` → `0719167688`)
- **Détection automatique** du type d'identifiant (email vs téléphone)
- **Inscription parent sans email obligatoire** (V1) — email devient `nullable`
- **Téléphone unique** en base pour identifier les parents
- **Redirection automatique** selon le rôle après login (admin / parent / répétiteur)

#### 👨‍👩‍👧 Espace Parent

- Tableau de bord avec derniers événements
- Création de demande de répétiteur (formulaire multi-étapes : élève, besoin, organisation)
- Liste des demandes avec statuts différenciés
- Réception des propositions de MON MAÎTRE
- Acceptation / refus des propositions (avec raison)
- Suivi des placements
- **Bouton d'appel direct** vers MON MAÎTRE (`07 19 16 76 88`) — bloc dashboard + bouton flottant
- **Suivi des séances** effectuées par le répétiteur
- **Validation ou contestation** des séances déclarées
- **Évaluation du répétiteur** (note sur 10 + commentaire)
- **Historique complet** des séances

#### 👨‍🏫 Espace Répétiteur

- Tableau de bord avec statut du profil
- **Profil complet** : niveau d'études, diplôme, matières, classes, zones, disponibilités
- **Upload de documents** : CV, diplômes, certificats (PDF, JPG, PNG)
- **Photo de profil** : upload, affichage dans toutes les vues (10 emplacements), suppression
- **Enregistrement des séances** effectuées (date, durée, contenu, statut)
- **Consultation des missions** confirmées
- **Consultation des évaluations** reçues
- Envoi de la candidature après complétion du profil

#### 🛡️ Espace Admin (MON MAÎTRE)

- **Tableau de bord** avec 8 KPI statistiques
- **Gestion des demandes** : filtre par statut, recherche, changement de statut
- **Gestion des répétiteurs** : validation, refus, suspension, changement de statut
- **Aperçu et téléchargement des CV** (iframe + boutons)
- **Système de matching intelligent** :
    - Score de pertinence 0-100 basé sur 4 critères (matières, zone, niveau, disponibilité)
    - Détail du score affiché à l'admin
    - Proposition au parent en un clic
- **Suivi des placements** avec recherche et filtres
- **Tableau de bord de suivi** avec :
    - 4 KPI principaux (placements actifs, séances, taux présence, note moyenne)
    - Graphique linéaire (séances des 12 dernières semaines)
    - Graphique doughnut (répartition par statut)
    - Alertes automatiques (inactivité, absences répétées, notes basses)
- **Arbitrage des séances contestées** (valider / confirmer la contestation)
- **Export CSV** des placements (compatible Excel FR, avec filtres)

#### 🔔 Notifications

- **Système de notifications in-app** complet
- **Badge compteur** dans le header avec icône cloche
- **Dropdown** au clic (5 dernières notifications)
- **Page dédiée** `/notifications` avec filtres (toutes / non lues / lues)
- **Pagination** discrète en français
- **8 événements** déclenchant des notifications :
    1. Nouvelle proposition → notif **Parent**
    2. Proposition acceptée → notif **Admin**
    3. Proposition refusée → notif **Admin**
    4. Placement créé → notif **Parent + Répétiteur**
    5. Statut demande changé → notif **Parent**
    6. Profil validé → notif **Répétiteur**
    7. Nouvelle séance déclarée → notif **Parent**
    8. Séance validée / contestée / arbitrée → notif aux parties concernées

#### 📄 Pages publiques

- **Page d'accueil** complète :
    - Hero plein écran avec **carousel en arrière-plan** (3 slides + overlay)
    - Section Fonctionnement (4 étapes)
    - Section Services (6 services)
    - Section FAQ (6 questions en accordéon)
    - Section Contact (téléphone, WhatsApp, email)
    - CTA final
- Formulaires de **pré-inscription** (parent + répétiteur)

#### 🎨 Composants CSS

- Classes utilitaires custom : `card-hover`, `pattern-dots`, `pattern-grid`
- Boutons : `btn-primary`, `btn-accent`, `btn-secondary`, `btn-outline`
- Badges et inputs cohérents
- Animations : `fade-in`, `slide-up`, `slide-in-left/right`, `float`, `animate-on-scroll`

#### 🛡️ Sécurité

- **Rate limiting** sur le login (5 tentatives / minute)
- **Rate limiting** sur la pré-inscription (5 tentatives / heure / IP)
- **Rate limiting** sur les formulaires (30 / heure)
- **Headers HTTP de sécurité** :
    - `X-Frame-Options: SAMEORIGIN`
    - `X-Content-Type-Options: nosniff`
    - `X-XSS-Protection: 1; mode=block`
    - `Referrer-Policy: strict-origin-when-cross-origin`
    - `Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()`
- **HTTPS forcé** en production (HSTS)
- Vérification d'accès sur toutes les actions sensibles (ownership)

#### 📄 Pages d'erreur personnalisées

- **404** — Page introuvable
- **403** — Accès refusé
- **500** — Erreur technique
- **419** — Session expirée
- **503** — Maintenance en cours
  Toutes avec logo, palette MON MAÎTRE et actions contextuelles.

#### 📚 Documentation

- `README.md` complet (installation, structure, déploiement)
- `CHANGELOG.md` (ce fichier)
- `CONTRIBUTING.md` (conventions de code)
- `.env.example` mis à jour

---

### 🔧 Modifié

- **Refonte totale de la charte graphique** : ancienne palette (bleu nuit `#132D39`, vert sauge `#55766F`, doré `#E7C273`) → nouvelle palette officielle
- **Auth Breeze désactivée** : l'inscription passe uniquement par `/pre-inscription/parent` ou `/pre-inscription/repetiteur`
- **Formulaire de demande** : suppression du champ "niveau scolaire"
- **Formulaire parent** : suppression du champ email obligatoire
- **Pagination** : remplacement des vues par défaut par une pagination discrète en français

---

### 🐛 Corrigé

- **Collision de contrôleur** `Admin\DashboardController` (stub vs complet) → version complète restaurée
- **Routes orphelines** `route('dashboard')` dans les contrôleurs Breeze → redirections selon le rôle
- **Section FAQ et Contact manquantes** dans la page d'accueil (liens orphelins dans le menu)
- **Police Figtree** remplacée par **Inter** partout
- **Doublon "Alternative parent"** dans `pre-inscription/repetiteur.blade.php`
- **MatchingService** : sécurité sur les valeurs null et sensibilité à la casse

---

### 🔐 Sécurité (correctifs)

- **Seeders non idempotents** → `firstOrCreate` et `updateOrCreate`
- **Vérification d'ownership** ajoutée sur toutes les actions sensibles (placement, séance, évaluation, document)
- **Vérification de rôle** systématique sur les routes protégées

---

### 🗄️ Base de données

#### Nouvelles tables

- `seances` — Suivi des séances effectuées
- `evaluations` — Évaluations des répétiteurs par les parents
- `notifications` — Notifications in-app (table Laravel native)

#### Colonnes ajoutées

- `users.email` → nullable (parents)
- `users.phone` → unique
- `repetiteurs.photo` → photo de profil
- `seances.validee_at`, `seances.validee_par`, `seances.raison_contestation`
- Nouveau statut `en_attente` et `contested` sur `seances.statut`

---

### 📝 Notes techniques

- **Laravel 13** + **PHP 8.3**
- **Base de données** : SQLite (dev) / MySQL (prod)
- **Front** : Blade + Alpine.js + Tailwind CSS + Chart.js
- **Auth** : Login unifié (email OU téléphone)
- **Rôles** : Spatie Laravel Permission (parent, repetiteur, admin)
- **Notifications** : Laravel Notifications (canal `database`)
- **Storage** : `local` pour documents, `public` pour photos

---

## Format des versions

- **MAJEUR** : changements incompatibles avec les versions précédentes
- **MINEUR** : nouvelles fonctionnalités rétrocompatibles
- **CORRECTIF** : corrections de bugs rétrocompatibles

---

## Types de modifications

- `Ajouté` pour les nouvelles fonctionnalités
- `Modifié` pour les changements de fonctionnalités existantes
- `Déprécié` pour les fonctionnalités bientôt supprimées
- `Supprimé` pour les fonctionnalités retirées
- `Corrigé` pour les corrections de bugs
- `Sécurité` pour les correctifs de vulnérabilités

## [1.0.1] - 2026-09-29

### Ajouté

- Section "Nos répétiteurs" sur la page d'accueil (vrais profils validés)
- Blog section avec 3 actualités (CEPE, BEPC, BAC)
- Section "Nos valeurs" (6 valeurs)
- 4 pages légales (CGU, Confidentialité, Cookies, Mentions)
- Matière Informatique dans les formulaires
- Bandeau d'activation des notifications push
- Commande artisan `repetiteurs:photos-force`
- Commande artisan `repetiteurs:nettoyer-photos`

### Modifié

- Section Contact (design premium avec CTA fort)
- Section Services (espacement optimisé)

### Corrigé

- Cache des images de profil (no-cache + ETag)
- Liaison user_id ↔ repetiteurs pour les 4 répétiteurs de test
