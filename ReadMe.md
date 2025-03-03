-> Installer les dépendances
-> faire le .env.local

symfony console make:migration
symfony console doctrine:migrations:migrate
symfony server:start

Sur votre navigateur mettez l'adresse http://localhost:8000/