# Goldman - Site d'E-commerce de Luxe

## Description
Goldman est un site d'e-commerce de luxe, développé avec Symfony , chacun peut vendre ou acheter un article.

## Prérequis

### Outils nécessaires
- [Docker](https://www.docker.com/)
- [Symfony](https://symfony.com/)
- [PHP](https://www.php.net/)

## Installation

Remplissez d'abord le fichier .env.local avec vos identifiants mysql
pensez a remplir la DATABASE_URL et changeant user ,password et database_name

### 1. Démarrer Docker
```sh
docker compose up -d
```

### 2. Initialiser la base de données
```sh
composer install
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load --no-interaction
```

### 3. Lancer le serveur Symfony
```sh
symfony server:start
```

### 4. Accéder à l'application

Rendez-vous sur http://localhost:8000.
