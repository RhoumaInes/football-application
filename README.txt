# Projet Football App

Ce projet est une application web composée de deux parties :
- **Backend** : Symfony Version 6 (PHP)
- **Frontend** : Angular 13

## Contenu des Livrables
- **Code source du backend Symfony** : Ce répertoire contient l'API backend qui gère les données et la logique métier.
- **Code source du frontend Angular** : Ce répertoire contient l'application frontend construite avec Angular.
- **Instructions pour exécuter l'application** : 
Vous trouverez ci-dessous les instructions détaillées pour configurer et exécuter les deux parties de l'application.

---

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

### Backend Symfony
- PHP >= 8.0
- Composer (pour la gestion des dépendances PHP)
- Base de données (MySQL) et création d'une bdd "footballapp"

### Frontend Angular
- Node.js 
- NPM (Node Package Manager)
- Angular CLI

---

## Installation et Exécution du Backend (Symfony)

1. **Extraire le ZIP du backend** dans un répertoire de votre choix.

2. **Installer les dépendances PHP** avec Composer :

    ```bash
    cd backendfootballapp
    composer install
    ```

3. **Configurer la base de données** :
    - Créez une base de données MySQL (`footballapp`).

4. **Exécuter les migrations** pour créer les tables dans la base de données :

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

5. **Démarrer le serveur Symfony** :

    ```bash
    symfony serve -d
    ```

   L'API backend sera maintenant disponible à `http://127.0.0.1:8000`.

---

## Installation et Exécution du Frontend (Angular)

1. **Extraire le ZIP du frontend** dans un répertoire de votre choix.

2. **Installer les dépendances Node.js** :

    ```bash
    cd football-app
    npm install
    ```

3. **Démarrer le serveur de développement Angular** :

    ```bash
    ng serve
    ```

    L'application frontend sera maintenant disponible à `http://localhost:4200`.

---

## Test de l'application

1. **Backend Symfony** :
    - Utilisez des outils comme [Postman](https://www.postman.com/) pour tester les points d'API du backend.
    - Exemple de points d'API :
        - `POST /login` : Authentification des utilisateurs.
        - Le reste des points d'API sont détaillés dans l'énoncé du test.

2. **Frontend Angular** :
    - Accédez à l'URL `http://localhost:4200` dans votre navigateur.
    - Vous pouvez vous connecter avec les informations d'utilisateur que vous avez définies dans la base de données en utilisant "doctrine-fixtures" avec la commande suivante :

    ```bash
    php bin/console doctrine:fixtures:load
    ```

    Cela va créer un utilisateur avec les identifiants suivants : 
    - **Nom d'utilisateur** : `testuser`
    - **Mot de passe** : `password`

    - Utilisez les fonctionnalités de l'application pour interagir avec les données (les liens de la navigation et les boutons).

---

## Structure du Projet

### Backend Symfony

- `src/` : Contient les contrôleurs, services, entités et autres fichiers métier.
- `config/` : Contient les fichiers de configuration de Symfony.
- `public/` : Dossier public contenant le fichier d'entrée `index.php`.
- `templates/` : Contient les vues Twig.
- `translations/` : Contient les fichiers de traduction pour l'internationalisation.

### Frontend Angular

- `src/app/` : Contient tous les composants, services et autres éléments d'Angular.
- `src/environments/` : Contient les configurations d'environnement pour le développement et la production.
- `angular.json` : Fichier de configuration global d'Angular.
- `package.json` : Fichier de configuration des dépendances Node.js.

---