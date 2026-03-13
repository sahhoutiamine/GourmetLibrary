# GourmetLibrary API Documentation

Bienvenue dans l'API de **GourmetLibrary**, la médiathèque culinaire.

## 🛠 Configuration rapide

*   **Serveur** : Lancez le serveur avec `php artisan serve`.
*   **Base de données** : SQLite par défaut. `php artisan migrate --seed` a déjà été exécuté.
*   **Postman** : Importer le fichier `GourmetLibrary.postman_collection.json`.

## 👥 Rôles Utilisateurs

*   **Admin** (`role: admin`) : Peut tout faire.
*   **Reader (Gourmand)** (`role: reader`) : Consulter et rechercher des livres.

### 🔑 Comptes de test (Seedés)

| Nom | Email | Mot de passe | Rôle |
| :--- | :--- | :--- | :--- |
| Chef Bibliothécaire | admin@gourmet.com | password | admin |
| Amine Gourmand | reader@gourmet.com | password | reader |

---

## 🛣 Endpoints

### 🔐 Authentification
*   `POST /api/register` : Inscription
*   `POST /api/login` : Connexion (retourne un token Bearer)
*   `POST /api/logout` : Déconnexion (auth requis)

### 📚 Catégories
*   `GET /api/categories` : Liste toutes les catégories.
*   `GET /api/categories/{id}` : Détails d'une catégorie.
*   `GET /api/categories/{id}/books` : Livres d'une catégorie (supporte `?title={term}`). *(En tant que gourmand, je souhaite consulter la liste des livres de cuisine disponibles dans une catégorie)*
*   `POST /api/categories` (**Admin**) : Créer une catégorie.
*   `PUT /api/categories/{id}` (**Admin**) : Modifier une catégorie.
*   `DELETE /api/categories/{id}` (**Admin**) : Supprimer une catégorie.

### 📖 Livres
*   `GET /api/books` : Liste tous les livres (supporte `?new_arrivals=1`, `?popular=1` ou `?title={term}`). *(En tant que gourmand, je souhaite voir les livres les plus populaires ou les nouveaux arrivages)*
*   `GET /api/books/search?q={term}` : Rechercher par titre, auteur ou catégorie (supporte aussi `?title={term}` pour recherche spécifique). *(En tant que gourmand, je veux pouvoir rechercher un livre par son titre, son auteur ou sa catégorie)*
*   `POST /api/books` (**Admin**) : Ajouter un livre (crée automatiquement les copies).
*   `PUT /api/books/{id}` (**Admin**) : Modifier un livre.
*   `DELETE /api/books/{id}` (**Admin**) : Supprimer un livre.
*   `PUT /api/books/{bookId}/copies/{copyId}` (**Admin**) : Mettre à jour l'état d'un exemplaire. *(En tant qu'administrateur, je souhaite pouvoir voir la quantité de livres dégradés)*

### 📊 Statistiques (Admin Uniquement)
*   `GET /api/stats/dashboard` : État global de la collection, livres populaires, répartition par catégorie. *(En tant qu'administrateur, je veux visualiser des statistiques sur la collection)*
*   `GET /api/stats/degraded` : Rapport sur les livres dégradés/tachés. *(Quantité de livres dégradés pour planifier leur réparation)*
