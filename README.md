# Touche pas au klaxon

Application interne de covoiturage entre les différents sites d'une entreprise. Les employés consultent les trajets à venir, se connectent pour proposer un trajet ou en modifier un dont ils sont l'auteur, et un administrateur gère les agences (villes), consulte la liste des utilisateurs et supervise l'ensemble des trajets.

## Stack technique

- PHP 8.1+, architecture MVC "maison" (sans framework), routage via [izniburak/router](https://packagist.org/packages/izniburak/router)
- MySQL / MariaDB (PDO, requêtes préparées)
- Bootstrap 5 + Sass (variables Bootstrap surchargées avec la palette imposée)
- PHPUnit (tests unitaires et d'intégration)
- PHPStan (analyse statique, niveau 6)

## Installation

Prérequis : PHP >= 8.1 (avec `pdo_mysql`), Composer, MySQL/MariaDB, Node.js/npm.

```bash
composer install
npm install
npm run build:css
```

Copier le fichier d'environnement et l'ajuster si besoin (identifiants de connexion à la base) :

```bash
cp .env.example .env
```

Créer le schéma puis importer le jeu d'essai fourni en annexe (agences et utilisateurs) :

```bash
mysql -u root < database/schema.sql
php database/seed.php
```

Lancer le serveur de développement PHP, servi depuis `public/` :

```bash
php -S localhost:8000 -t public
```

L'application est alors accessible sur http://localhost:8000.

## Comptes de test

Tous les comptes importés par `database/seed.php` partagent le même mot de passe.

| Rôle      | Email                          | Mot de passe   |
|-----------|---------------------------------|----------------|
| Admin     | alexandre.martin@email.fr       | `Password123!` |
| Employé   | sophie.dubois@email.fr          | `Password123!` |

(les 18 autres employés du jeu d'essai utilisent le même mot de passe, avec leur propre email)

## Tests et qualité

```bash
composer stan   # PHPStan (niveau 6) sur src/
composer test   # PHPUnit (tests unitaires + intégration)
```

Les tests d'intégration utilisent une base dédiée (`touche_pas_au_klaxon_test`, voir `.env.testing`), recréée automatiquement à partir de `database/schema.sql` à chaque exécution de la suite. Chaque test s'exécute dans une transaction annulée à la fin, garantissant leur isolation.

## Structure du projet

```
public/         Point d'entrée HTTP (front controller, assets compilés)
routes/         Déclaration des routes
src/
  Config/       Connexion PDO, chargement du .env
  Core/         Contrôleur de base, authentification, CSRF, messages flash
  Controllers/  Contrôleurs (accueil, connexion, trajets, administration)
  Repositories/ Accès aux données (agences, utilisateurs, trajets)
  Validation/   Règles de validation métier, indépendantes de la BDD
  Exceptions/   Exceptions applicatives (404, 403, validation)
views/          Vues PHP (layout, pages, partiels)
database/       Schéma SQL, script de seed, jeu d'essai, MCD/MLD
scss/           Sources Sass (variables + import Bootstrap)
tests/          Tests PHPUnit (Unit/ et Feature/)
```

## Modèle de données

Voir [`database/mcd.md`](database/mcd.md) (modèle conceptuel) et [`database/mld.txt`](database/mld.txt) (modèle logique). Le script de création est dans [`database/schema.sql`](database/schema.sql).

## Fonctionnalités

- **Page d'accueil** (publique) : liste des trajets à venir avec places disponibles, triés par date de départ.
- **Connexion** : email + mot de passe, session sécurisée (régénération de l'identifiant de session, CSRF sur tous les formulaires).
- **Employé connecté** : détails d'un trajet (contact, téléphone, email, places totales) dans une fenêtre modale ; création d'un trajet ; modification/suppression des trajets dont il est l'auteur.
- **Administrateur** : liste des utilisateurs (lecture seule), gestion complète des agences (création/modification/suppression), liste et suppression de tous les trajets.
