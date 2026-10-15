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

## 📸 Aperçu

### 🏠 Page d'accueil

![Page d'accueil](docs/screenshots/01-accueil.png)
_*Hero avec carousel, sections Services, FAQ, Contact*_

### 🛡️ Dashboard Admin

![Dashboard admin](docs/screenshots/02-admin-dashboard.png)
_*KPI, dernières demandes, derniers répétiteurs*_

### 👨‍👩‍👧 Espace Parent

![Espace parent](docs/screenshots/03-parent-dashboard.png)
_*Suivi des demandes, propositions, placements*_

### 👨‍🏫 Espace Répétiteur

![Espace répétiteur](docs/screenshots/04-repetiteur-dashboard.png)
_*Profil, missions, séances, statistiques*_

### 📱 Version mobile

![Version mobile](docs/screenshots/05-mobile.png)
_*Interface responsive et PWA installable*_

### 🔔 Notifications Push

![Notifications](docs/screenshots/06-notifications.png)
_*Notifications in-app + push VAPID*_

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
- 📥 Exporter les placements en CSV

### 🔔 Transversal

- Notifications in-app avec badge compteur
- Login unifié (email OU téléphone)
- Pages d'erreur personnalisées (404, 403, 500…)
- Sécurité renforcée (rate limiting, headers HTTP, CSP)
- PWA installable (iOS, Android)
- Notifications push VAPID

---

## 🛠️ Stack technique

| Composant           | Version                              |
| ------------------- | ------------------------------------ |
| **Framework**       | Laravel 13                           |
| **PHP**             | 8.3+                                 |
| **Base de données** | MySQL (prod) / SQLite (dev)          |
| **Frontend**        | Blade + Alpine.js 3 + Tailwind CSS 3 |
| **Build**           | Vite 8                               |
| **Auth**            | Laravel Breeze (personnalisé)        |
| **Rôles**           | Spatie Laravel Permission v8         |
| **Graphiques**      | Chart.js 4                           |
| **Push**            | WebPush + VAPID                      |

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

# 8. Créer le lien storage
php artisan storage:link

# 9. Compiler les assets
npm run build

# 10. Lancer le serveur
php artisan serve
```
