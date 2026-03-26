# 📦 Yobbal — Plateforme de suivi de colis

Application web fullstack de gestion et de suivi de colis, développée pour le marché camerounais. Construite avec **Laravel 12**, **Blade** et **Tailwind CSS**, elle permet aux utilisateurs d'enregistrer leurs envois et de les suivre via un numéro de référence unique.

---

## 🚀 Stack technique

| Technologie | Usage |
|---|---|
| Laravel 12 | Framework PHP backend + routing + ORM |
| Blade | Templating côté serveur |
| Tailwind CSS v3 | Styles UI |
| Alpine.js | Interactivité légère côté client |
| Vite | Bundler assets frontend |
| MySQL / SQLite | Base de données relationnelle |
| Laravel Breeze | Authentification (login, register, reset) |
| Docker + Nginx | Conteneurisation & déploiement |

---

## 📁 Structure du projet

```
yobbal/
├── app/
│   ├── Http/Controllers/
│   │   ├── ColisController.php      # CRUD des colis
│   │   ├── TrackingController.php   # Suivi public par numéro
│   │   ├── HomeController.php       # Dashboard
│   │   └── ProfileController.php   # Profil utilisateur
│   └── Models/
│       ├── Colis.php                # Modèle colis
│       └── User.php                 # Modèle utilisateur
├── database/
│   ├── migrations/                  # Tables users, colis, cache, jobs
│   └── seeders/
├── resources/
│   └── views/
│       ├── colis/                   # Vues CRUD colis
│       ├── tracking/                # Vue de suivi public
│       ├── layouts/                 # Layouts principaux
│       └── welcome.blade.php        # Page d'accueil
├── routes/
│   ├── web.php                      # Routes principales
│   └── auth.php                     # Routes d'authentification
├── Dockerfile                       # Image Docker (nginx-php-fpm)
└── vite.config.js
```

---

## ⚙️ Installation

### Prérequis

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL ou SQLite

### Cloner et installer

```bash
git clone https://github.com/jonathan268/yobbal.git
cd yobbal
```

### Installation automatique (script setup)

```bash
composer run setup
```

Ce script exécute automatiquement :
1. `composer install`
2. Copie `.env.example` → `.env`
3. Génère la clé applicative (`APP_KEY`)
4. Lance les migrations (`php artisan migrate`)
5. `npm install` + `npm run build`

### Variables d'environnement

Modifier le fichier `.env` généré :

```env
APP_NAME=Yobbal
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yobbal
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### Lancer en développement

```bash
composer run dev
```

Lance en parallèle : serveur PHP, queue worker, logs Pail et Vite.

---

## 📖 Fonctionnalités

### 🌐 Suivi public (sans connexion)

N'importe qui peut suivre un colis sans créer de compte.

```
GET /track?n=YBL-000001
```

Le numéro de suivi suit le format `YBL-{ID}` (ex : `YBL-000042`).

---

### 🔐 Espace utilisateur (connecté)

| Page | URL | Description |
|---|---|---|
| Accueil / Dashboard | `/accueil` | Vue d'ensemble |
| Liste des colis | `/colis` | Mes colis en cours |
| Nouveau colis | `/colis/create` | Enregistrer un envoi |
| Détail colis | `/colis/{id}` | Voir un colis spécifique |
| Modifier colis | `/colis/{id}/edit` | Mettre à jour les infos |
| Supprimer colis | `/colis/{id}` (DELETE) | Supprimer un colis |
| Profil | `/profile` | Modifier mon compte |

---

### 🔑 Authentification (Laravel Breeze)

| Action | URL |
|---|---|
| Connexion | `/login` |
| Inscription | `/register` |
| Mot de passe oublié | `/forgot-password` |
| Réinitialisation | `/reset-password/{token}` |
| Vérification email | `/verify-email` |
| Déconnexion | `POST /logout` |

---

## 🗄️ Modèle de données

### Table `colis`

| Champ | Type | Description |
|---|---|---|
| `id` | bigint (auto) | Clé primaire |
| `user_id` | FK → users | Propriétaire du colis |
| `expediteur` | string | Nom de l'expéditeur |
| `destinataire` | string | Nom du destinataire |
| `ville_depart` | string | Ville d'origine |
| `ville_arrivee` | string | Ville de destination |
| `poids` | float | Poids en kg |
| `statut` | string | Statut du colis |
| `created_at / updated_at` | timestamp | Dates |

### Statuts possibles

```
en_attente → en_transit → livre
                        ↘ retourne
```

### Table `users`

| Champ | Type |
|---|---|
| `id` | bigint |
| `name` | string |
| `email` | string (unique) |
| `password` | string (hashé) |
| `email_verified_at` | timestamp |

---

## 🔒 Sécurité

- Authentification native Laravel (sessions, CSRF)
- Propriété vérifiée : un utilisateur ne peut voir que ses propres colis (`abort_if`)
- Validation Blade côté serveur sur toutes les entrées
- Hachage automatique des mots de passe (`bcrypt`)
- Reset de mot de passe par email avec token signé

---

## 🐳 Déploiement Docker

Un `Dockerfile` est inclus pour un déploiement en production avec **nginx + PHP-FPM** :

```bash
docker build -t yobbal .
docker run -p 80:80 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  yobbal
```

Variables d'environnement Docker configurées par défaut :

```
APP_ENV=production
APP_DEBUG=false
LOG_CHANNEL=stderr
WEBROOT=/var/www/html/public
```

---

## 🧪 Tests

```bash
composer run test
# ou
php artisan test
```

---

## 📄 Licence

MIT © [jonathan268](https://github.com/jonathan268)
