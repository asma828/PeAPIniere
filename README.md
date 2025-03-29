# 🌿 Pépinière API

## 📌 Objectifs
Nous sommes une pépinière en pleine croissance et souhaitons améliorer notre efficacité et notre productivité. Cette API permettra de :
- Gérer les stocks et les ventes de plantes.
- Améliorer l'expérience des clients.
- Administrer les plantes et leurs catégories.
- Gérer les rôles et permissions des utilisateurs.

## 🚀 Technologies Utilisées
- Laravel (Framework PHP)
- Laravel Sanctum (Authentification JWT)
- Spatie Sluggable (Génération de slugs)
- Laravel Query Builder (Statistiques)
- PostgreSQL (Base de données)
- Postman (Tests API)
- Swagger (Documentation API)

## 👥 Rôles Utilisateurs
1. **Client** :
   - Inscription et connexion (JWT).
   - Consultation des plantes et de leurs détails.
   - Passer et annuler une commande.
   - Suivre l'état de sa commande.

2. **Employé** :
   - Connexion avec permissions adaptées.
   - Gestion des commandes (préparation et livraison).

3. **Administrateur** :
   - Gestion des catégories et des plantes (CRUD).
   - Consultation des statistiques sur les ventes et les tendances.

4. **Développeur** :
   - Implémentation des tests unitaires et gestion des exceptions.
   - Documentation et validation de l'API avec Postman et Swagger.
   - Gestion des interactions avec la base de données via DAO.

## 📌 Endpoints Principaux
### 🔑 Authentification
- `POST /api/register` : Inscription d'un client.
- `POST /api/login` : Connexion et obtention du jeton JWT.

### 🪴 Gestion des Plantes
- `GET /api/plants` : Liste des plantes disponibles.
- `GET /api/plants/{slug}` : Détails d'une plante spécifique.
- `POST /api/plants` : Ajouter une plante (Admin uniquement).
- `PUT /api/plants/{id}` : Modifier une plante (Admin uniquement).
- `DELETE /api/plants/{id}` : Supprimer une plante (Admin uniquement).

### 🏷 Gestion des Catégories
- `GET /api/categories` : Liste des catégories.
- `POST /api/categories` : Ajouter une catégorie (Admin uniquement).
- `PUT /api/categories/{id}` : Modifier une catégorie (Admin uniquement).
- `DELETE /api/categories/{id}` : Supprimer une catégorie (Admin uniquement).

### 🛒 Gestion des Commandes
- `POST /api/orders` : Passer une commande.
- `GET /api/orders/{id}` : Voir l'état d'une commande.
- `PUT /api/orders/{id}/cancel` : Annuler une commande avant préparation.
- `PUT /api/orders/{id}/prepare` : Indiquer qu'une commande est prête (Employé).

### 📊 Statistiques
- `GET /api/stats/sales` : Statistiques des ventes.
- `GET /api/stats/popular-plants` : Plantes les plus populaires.
- `GET /api/stats/categories` : Répartition des ventes par catégorie.

## 🛠 Installation
1. Cloner le projet :
   ```sh
   git clone https://github.com/votre-repo/pepiniere-api.git
   cd pepiniere-api
   ```
2. Installer les dépendances :
   ```sh
   composer install
   ```
3. Configurer l'environnement :
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
4. Configurer la base de données dans `.env`.
5. Exécuter les migrations :
   ```sh
   php artisan migrate --seed
   ```
6. Lancer le serveur :
   ```sh
   php artisan serve
   ```

## ✅ Tests
- Lancer les tests unitaires :
  ```sh
  php artisan test
  ```
- Tester l'API avec Postman en important le fichier `postman_collection.json`.
- Générer la documentation avec Swagger.

## 🔒 Sécurité
- Utilisation de JWT pour l'authentification.
- Gestion des erreurs et codes HTTP adaptés.
- Validation des entrées pour éviter les injections SQL et XSS.

## 📝 Contributions
Les contributions sont les bienvenues ! Merci de suivre les bonnes pratiques de développement et de soumettre des PR bien documentées.

## 📜 Licence
Ce projet est sous licence MIT.
