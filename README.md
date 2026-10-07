<p align="center">
    <img src="public/images/logo-full.png" width="400" alt="MON MAÎTRE">
</p>

<h1 align="center">MON MAÎTRE</h1>

<p align="center">
    <strong>Le bon répétiteur, pour un meilleur avenir.</strong><br>
    Plateforme de placement et de suivi de répétiteurs à domicile en Côte d'Ivoire.
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel" alt="Laravel 13">
    <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php" alt="PHP 8.3">
    <img src="https://img.shields.io/badge/TailwindCSS-3.x-38BDF8?style=flat-square&logo=tailwind-css" alt="TailwindCSS">
    <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpine.js" alt="Alpine.js">
    <img src="https://img.shields.io/badge/License-Proprietary-red?style=flat-square" alt="License">
</p>

---

## 🎯 À propos

**MON MAÎTRE** est une plateforme ivoirienne de mise en relation entre **parents** et **répétiteurs à domicile**.

Le service fonctionne en **placement assisté** : les parents décrivent leur besoin, MON MAÎTRE recherche dans sa base, sélectionne les meilleurs profils, les propose aux parents, puis assure le **suivi complet** de la mission (séances, évaluations, arbitrage).

> **CONFIANCE • SOUTIEN • RÉUSSITE**

---

## ✨ Fonctionnalités principales

### 👨‍👩‍👧 Espace Parent

- 📝 Créer une demande de répétiteur (élève, matières, disponibilités, objectifs)
- 📬 Consulter et accepter/refuser les propositions de MON MAÎTRE
- 📊 Suivre les séances effectuées
- ✅ Valider ou contester les séances déclarées
- ⭐ Évaluer le répétiteur (note sur 10 + commentaire)
- 📞 Bouton d'appel direct vers MON MAÎTRE

### 👨‍🏫 Espace Répétiteur

- 📋 Compléter son profil complet (académique, compétences, zone, disponibilités)
- 📎 Uploader ses documents (CV, diplômes, certificats)
- 📸 Ajouter une photo de profil
- 📅 Enregistrer les séances effectuées
- 🎯 Consulter ses missions et évaluations reçues

### 🛡️ Espace Admin (MON MAÎTRE)

- 📊 Tableau de bord avec KPIs et graphiques
- ✅ Valider / refuser / suspendre les profils répétiteurs
- 🎯 Système de matching intelligent (score 0-100)
- 💼 Créer et suivre les propositions
- 📈 Dashboard de suivi des placements avec alertes
- ⚖️ Arbitrer les séances contestées
- 📥 Exporter les placements en CSV

### 🔔 Transversal

- Notifications in-app avec badge compteur
- Login unifié (email OU téléphone)
- Pages d'erreur personnalisées
- Sécurité renforcée (rate limiting, headers HTTP)

---

## 🛠️ Stack technique

| Composant           | Version                              |
| ------------------- | ------------------------------------ |
| **Framework**       | Laravel 13                           |
| **PHP**             | 8.3+                                 |
| **Base de données** | MySQL (prod) / SQLite (dev)          |
| **Frontend**        | Blade + Alpine.js 3 + Tailwind CSS 3 |
| **Build**           | Vite                                 |
| **Auth**            | Laravel Breeze (personnalisé)        |
| **Rôles**           | Spatie Laravel Permission v8         |
| **Graphiques**      | Chart.js 4                           |

---

## 🚀 Installation

### Prérequis

- PHP **8.3+**
- Composer
- Node.js **18+** & npm
- MySQL **8+** (ou SQLite pour dev)
- XAMPP, Laragon, ou équivalent

### Étapes d'installation

```bash
# 1. Cloner le projet
git clone <repo-url> monmaitre
cd monmaitre

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JS
npm install

# 4. Copier le fichier d'environnement
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

# 6. Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_DATABASE=monmaitre
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Lancer les migrations + seeders
php artisan migrate --seed

# 8. Créer le lien storage (pour les photos/documents)
php artisan storage:link

# 9. Compiler les assets
npm run build

# 10. Lancer le serveur
php artisan serve
```

L'application sera accessible sur **`http://localhost:8000`**

---

## 🔑 Comptes de test

| Rôle           | Identifiant                  | Mot de passe     |
| -------------- | ---------------------------- | ---------------- |
| **Admin**      | `admin@monmaitre.ci`         | `MonMaitre2026!` |
| **Parent**     | `0701020304` (téléphone)     | `password123`    |
| **Répétiteur** | `jean.koffi@test.ci` (email) | `password123`    |

> ⚠️ **Important** : Changez ces identifiants **avant la mise en production** !

---

## 📁 Structure du projet

```
monmaitre/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                → Espace admin (MON MAÎTRE)
│   │   │   ├── Auth/                 → Authentification (Breeze modifié)
│   │   │   ├── ParentSpace/          → Espace parent
│   │   │   ├── Repetiteur/           → Espace répétiteur
│   │   │   ├── NotificationController.php
│   │   │   ├── PreInscriptionController.php
│   │   │   ├── ProfileController.php
│   │   │   └── PublicController.php
│   │   ├── Middleware/
│   │   │   └── SecurityHeaders.php
│   │   └── Requests/                 → Validation des formulaires
│   ├── Models/
│   │   ├── User.php
│   │   ├── Eleve.php
│   │   ├── Demande.php
│   │   ├── Repetiteur.php
│   │   ├── Document.php
│   │   ├── Proposition.php
│   │   ├── Placement.php
│   │   ├── Seance.php
│   │   └── Evaluation.php
│   ├── Notifications/                → 8 classes Notification
│   ├── Services/
│   │   └── MatchingService.php       → Algorithme de matching
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/                   → Toutes les migrations
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       └── AdminSeeder.php
│
├── resources/
│   ├── css/
│   │   └── app.css                   → Tailwind + composants custom
│   ├── js/
│   │   └── app.js                    → Alpine.js + scripts
│   └── views/
│       ├── admin/                    → Vues espace admin
│       ├── auth/                     → Login, register, reset
│       ├── components/               → Composants Blade réutilisables
│       │   └── icons/                → 18 icônes SVG
│       ├── errors/                   → 404, 403, 500, 419, 503
│       ├── layouts/                  → 5 layouts principaux
│       ├── notifications/            → Page notifications
│       ├── parent/                   → Vues espace parent
│       ├── pre-inscription/          → Formulaires publics
│       ├── profile/                  → Profil Breeze
│       ├── public/                   → Page d'accueil
│       └── repetiteur/               → Vues espace répétiteur
│
├── routes/
│   ├── web.php                       → Routes principales
│   └── auth.php                      → Routes authentification
│
└── public/
    ├── images/
    │   ├── logo-full.png             → Logo complet
    │   ├── logo-icon.png             → Icône seule
    │   └── carousel/                 → Images carousel
    └── build/                        → Assets compilés
```

---

## 🎨 Charte graphique

### Palette officielle

| Rôle                | HEX       | Utilisation         |
| ------------------- | --------- | ------------------- |
| **Bleu principal**  | `#0B3D91` | Marque, titres      |
| **Bleu marine**     | `#062B5C` | Fonds foncés, admin |
| **Bleu secondaire** | `#143B8F` | Répétiteur, boutons |
| **Or / Doré**       | `#F5A623` | Accents, CTA        |
| **Blanc**           | `#FFFFFF` | Fond, contraste     |
| **Anthracite**      | `#1E1E1E` | Texte foncé         |
| **Gris clair**      | `#F5F6FA` | Fonds de pages      |

### Typographie

- **Police** : Inter (Google Fonts)
- **Poids** : 400, 500, 600, 700, 800

### Différenciation des espaces

- 🔵 **Admin** : header navy foncé
- 🔵 **Parent** : header blanc + accents navy
- 🔷 **Répétiteur** : header blanc + accents bleu vif
- ⚪ **Public** : header blanc + logo complet

## Sécurité - Scan antivirus

En production, installer ClamAV pour scanner tous les fichiers uploadés.
Voir : https://github.com/sunspikes/clamav-validator

---

## Sécurité — Vérification XSS

Commande pour vérifier les `{!! !!}` avec user input :
\`\`\`bash
findstr /S /I "{!!" resources\views\*.blade.php | findstr /V "vendor"
\`\`\`
Tout résultat doit être vérifié manuellement.

## 🔧 Commandes utiles

```bash
# Développement (assets + serveur)
npm run dev
php artisan serve

# Production (assets compilés)
npm run build
php artisan optimize

# Vider les caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# Base de données
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed --class=RoleSeeder

# Storage
php artisan storage:link

# Notifications (nettoyage)
php artisan tinker --execute="DB::table('notifications')->truncate();"
```

---

## 🔐 Sécurité

### Mesures en place

- ✅ **Rate limiting** sur le login (5 tentatives / minute)
- ✅ **Rate limiting** sur la pré-inscription (5 tentatives / heure / IP)
- ✅ **Rate limiting** sur les formulaires sensibles (30 / heure)
- ✅ **Headers HTTP** de sécurité (X-Frame-Options, HSTS, etc.)
- ✅ **HTTPS forcé** en production
- ✅ **Vérification d'ownership** sur toutes les actions sensibles
- ✅ **Mots de passe hachés** (BCRYPT 12 rounds)
- ✅ **Protection CSRF** sur tous les formulaires

### Recommandations production

- ⚠️ Changer les mots de passe par défaut
- ⚠️ Utiliser HTTPS (Let's Encrypt)
- ⚠️ Sauvegardes automatiques de la BDD
- ⚠️ Configurer un SMTP réel (Mailgun, Gmail, Brevo…)
- ⚠️ Activer les logs d'erreurs (Sentry, Bugsnag)

---

## 🚢 Déploiement en production

### Checklist avant mise en ligne

- [ ] `.env` configuré (`APP_ENV=production`, `APP_DEBUG=false`)
- [ ] Base de données MySQL créée et configurée
- [ ] **Mot de passe admin changé**
- [ ] Numéros de téléphone vérifiés
- [ ] SMTP configuré
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `npm run build`
- [ ] `php artisan optimize`
- [ ] HTTPS activé
- [ ] Sauvegardes configurées

### Commandes de déploiement

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci && npm run build
php artisan optimize
```

---

## 📞 Support & Contact

- 📱 **Téléphone** : [07 19 16 76 88](tel:+2250719167688)
- 💬 **WhatsApp** : [Discuter](https://wa.me/2250719167688)
- 📧 **Email** : [contact@monmaitre.ci](mailto:contact@monmaitre.ci)
- 🌍 **Pays** : Côte d'Ivoire

---

## 📄 Licence

**© 2026 MON MAÎTRE** — Tous droits réservés.

Ce logiciel est propriétaire. Toute reproduction, distribution ou utilisation non autorisée est interdite.

---

<p align="center">
    Fait avec  en Côte d'Ivoire
</p>
