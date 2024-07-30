ENGLISH:
Introduction

This project provides a solid foundation for developing secure REST APIs in Symfony 7. It implements JWT (JSON Web Token) authentication with refresh tokens to manage long-lived sessions.
Installation

Prerequisites:

    PHP 8.1 or higher
    Composer

Cloning the project:
    Bash
    
    git clone https://github.com/ynov-module-m2-full-stack/backend.git
    cd backend


Installing dependencies:
    Bash
    
    composer install


Database configuration:

    Create a database for your project.
    Update the .env.local or .env.prod file with your database connection information.

Database migrations:
    Bash
    
    bin/console doctrine:migrations:migrate


Generating entities (optional):
If you need to define custom entities with Doctrine, use the following command (replace YourEntity with your entity name):
    Bash
    
    bin/console make:entity YourEntity


Configuring JWT and refresh tokens:

    Configuring the JWT passphrase:
        Important: Set a strong and unique passphrase in the .env.local or .env.prod file:

        JWT_PASSPHRASE=your_secret_and_very_long_passphrase

        Generating keys: Run the following command:
        Bash

        php bin/console lexik:jwt:generate-keypair


    Advanced configuration:
        Modify the configuration files config/packages/lexik_jwt_authentication.yaml and config/packages/security.yaml to customize JWT settings (token lifetime, issuer, etc.).

Starting the development server:
    Bash
    
    bin/console server:start 127.0.0.1:8000

Usage

    Consult the API documentation for endpoints and authentication details.
    To obtain an initial JWT token, follow the authentication procedure described in the documentation.
    Include the access JWT token in the Authorization header for subsequent API requests.

Routes

The API exposes the following routes:

    /api/login_check: Endpoint for user login. Accessible to all (PUBLIC_ACCESS).
    /api/doc: API documentation. Accessible to all (PUBLIC_ACCESS).
    /api/users: User management. Accessible to all (PUBLIC_ACCESS).
    /api/token/refresh: Refresh JWT token. Accessible to all (PUBLIC_ACCESS).
    /api: Entry point for protected resources. Accessible only to authenticated users (IS_AUTHENTICATED_FULLY).

The API manages event and invitation entities.

Security

    JWT passphrase: Ensure your JWT passphrase is strong and stored securely.
    Additional security measures: Consider implementing additional security measures such as rate limiting and IP whitelisting.
    
******************************
FRENCH VERSION :
Présentation

Ce projet fournit une base solide pour développer des API REST sécurisées en Symfony 7. Il implémente l'authentification JWT (JSON Web Token) avec des tokens de rafraîchissement pour gérer des sessions de longue durée.
Installation

    Prérequis:
        PHP 8.1 ou supérieur
        Composer

    Clonage du projet:
    Bash

    git clone https://github.com/ynov-module-m2-full-stack/backend.git
    cd backend


Installation des dépendances:
Bash

composer install


Configuration de la base de données:

    Créez une base de données pour votre projet.
    Mettez à jour le fichier .env.local ou .env.prod avec les informations de connexion à votre base de données.

Migrations de la base de données:
    Bash
    
    bin/console doctrine:migrations:migrate


Génération des entités (optionnel):
Si vous avez besoin de définir des entités personnalisées avec Doctrine, utilisez la commande suivante (remplacez VotreEntite par le nom de votre entité):
    Bash
    
    bin/console make:entity VotreEntite


Configuration de JWT et des tokens de rafraîchissement:

    Configuration de la phrase de passe JWT:
        Important: Renseignez une phrase de passe forte et unique dans le fichier .env.local ou .env.prod :

        JWT_PASSPHRASE=votre_phrase_de_passe_secrète_et_très_longue

        Génération des clés: Exécutez la commande suivante :
        Bash

        php bin/console lexik:jwt:generate-keypair


    Configuration avancée:
        Modifiez les fichiers de configuration config/packages/lexik_jwt_authentication.yaml et config/packages/security.yaml pour personnaliser les paramètres de JWT (durée de vie des tokens, émetteur, etc.).

Démarrage du serveur de développement:
    Bash
    
    bin/console server:start 127.0.0.1:8000


Utilisation

    Consultez la documentation de l'API pour connaître les points d'accès et les détails d'authentification.
    Pour obtenir un premier token JWT, suivez la procédure d'authentification décrite dans la documentation.
    Incluez le token JWT d'accès dans l'en-tête Authorization pour les requêtes API suivantes.

Routes

L'API expose les routes suivantes :

    /api/login_check: Point d'entrée pour la connexion des utilisateurs. Accessible à tous (PUBLIC_ACCESS).
    /api/doc: Documentation de l'API. Accessible à tous (PUBLIC_ACCESS).
    /api/users: Gestion des utilisateurs. Accessible à tous (PUBLIC_ACCESS).
    /api/token/refresh: Rafraîchissement du token JWT. Accessible à tous (PUBLIC_ACCESS).
    /api: Point d'entrée des ressources protégées. Accessible uniquement aux utilisateurs authentifiés (IS_AUTHENTICATED_FULLY).

L'API gère les entités event et invitation.

Sécurité

    Phrase de passe JWT: Assurez-vous que votre phrase de passe JWT est forte et stockée de manière sécurisée.
    Mesures de sécurité supplémentaires: Envisagez d'implémenter des mesures de sécurité supplémentaires comme la limitation de taux et la liste blanche d'IP.
