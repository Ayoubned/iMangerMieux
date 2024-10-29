# iMangerMieux
Une application Web permettant de maintenir un journal de tous les aliments consommés.

## Table des matières
1. [Structure des Fichiers](#structure-des-fichiers)
2. [Configuration de l'Environnement](#configuration-de-lenvironnement)
3. [Importer les Données dans la Base de Données](#importer-les-données-dans-la-base-de-données)
4. [Fonctionnalités CRUD](#fonctionnalités-crud)
5. [Tests Unitaires](#tests-unitaires)

---

## Structure des Fichiers

Le projet est structuré comme suit :

```
backend/
│
├── sql/
│   ├── aliments.csv           # Données pour la table 'aliment'
│   ├── database.sql            # Script SQL pour créer les tables
│   ├── users.csv               # Données pour la table 'utilisateur'
│
├── tests/
│   ├── test_aliments.php       # Tests CRUD pour 'aliment'
│   ├── test_journal.php        # Tests CRUD pour 'journal'
│   ├── test_users.php          # Tests CRUD pour 'utilisateur'
│
├── aliments.php                # API CRUD pour 'aliment'
├── journal.php                 # API CRUD pour 'journal'
├── users.php                   # API CRUD pour 'utilisateur'
├── config.php                  # Configuration (ex. paramètres de connexion)
├── csvinjection.php            # Importation CSV et prévention d'injection
├── init_db.php                 # Initialisation de la BDD à partir de 'database.sql'
├── init_pdo.php                # Connexion PDO à la base de données


---

## Configuration de l'Environnement

### Prérequis
1. **Serveur WAMP** : Installez un serveur local (WAMP, LAMP, MAMP, etc.).
2. **PHP** : Assurez-vous que PHP est installé.
3. **MySQL** : Utilisez MySQL via votre serveur local.

### Initialisation
1. Clonez le projet ou copiez tous les fichiers dans votre dossier de serveur WAMP.
2. Créez une base de données nommée `projet_idaw` dans **phpMyAdmin** ou un autre outil de gestion MySQL.
3. Modifiez `config.php` avec vos informations de connexion (hôte, utilisateur, mot de passe, etc.).

### Démarrage du Serveur
Lancez le serveur WAMP et assurez-vous que les fichiers sont accessibles via `http://localhost/imangermieux/backend/`.

---
## Importer les Données dans la Base de Données

Pour initialiser et remplir la base de données avec les fichiers CSV et le script SQL, suivez ces étapes :

### 1. Initialisation de la Base de Données
- Exécutez `init_db.php` pour créer les tables à partir de `database.sql`.
- Accédez à :
   ```
   http://localhost/imangermieux/backend/init_db.php
   ```
Une fois exécuté, ce script indique si la création des tables a réussi.

### 2. Importation des Données Aliments et Utilisateurs
- Exécutez `csvinjection.php` pour importer les données de `aliments.csv` et `users.csv` dans les tables `aliment` et `utilisateur`.
- Accédez à :
   ```
   http://localhost/imangermieux/backend/csvinjection.php
   ```
Ce script convertit aussi certains caractères risqués pour éviter l'injection CSV et insère les données dans les tables correspondantes.

---

## Fonctionnalités CRUD

### 1. Aliments
L'API pour les aliments, via `aliments.php`, permet :
- **GET** : Récupération d'un ou plusieurs aliments.
- **POST** : Ajout d'un nouvel aliment.
- **PUT** : Mise à jour d'un aliment existant.
- **DELETE** : Suppression d'un aliment.

### 2. Journal
Dans `journal.php` :
- **GET** : Récupération d'entrées de journal.
- **POST** : Ajout d'une nouvelle entrée.
- **PUT** : Mise à jour d'une entrée.
- **DELETE** : Suppression d'une entrée.

### 3. Utilisateurs
L'API `users.php` permet :
- **GET** : Récupération d'utilisateurs.
- **POST** : Ajout d'un nouvel utilisateur.
- **PUT** : Mise à jour d'un utilisateur existant.
- **DELETE** : Suppression d'un utilisateur.

---

## Tests Unitaires

Les tests unitaires se trouvent dans le dossier `tests/`.

### Exécution des Tests

Accédez aux fichiers de test via un navigateur :
   - `test_aliments.php` : Teste les opérations CRUD pour les aliments.
   - `test_journal.php` : Teste les opérations CRUD pour le journal.
   - `test_users.php` : Teste les opérations CRUD pour les utilisateurs.

Pour chaque test, ouvrez l'URL suivante dans votre navigateur :
   ```
   http://localhost/imangermieux/backend/tests/test_aliments.php
   http://localhost/imangermieux/backend/tests/test_journal.php
   http://localhost/imangermieux/backend/tests/test_users.php
   ```
Les résultats indiqueront si chaque opération CRUD a réussi ou échoué.

---


## Contact

Si vous avez des questions, veuillez contacter l'administrateur du projet ou consultez la documentation de PHP et MySQL pour plus de détails.
```