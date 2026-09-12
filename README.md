# Gestion University — Réservation de salles

<!-- markdownlint-disable MD001 MD024 MD025 MD036 -->

Application web de **gestion et de réservation de salles universitaires**, réalisée en **PHP orienté objet** avec **MySQL**.

Le projet a pour objectif de permettre à une université de gérer ses salles et de contrôler les réservations en appliquant les règles métier définies dans le cahier des charges.

---

## 📌 Présentation du projet

L'application permet de gérer deux éléments principaux :

* 🏫 les salles universitaires ;
* 📅 les réservations de salles.

Une réservation ne peut être enregistrée que si toutes les règles métier sont respectées, notamment la disponibilité de la salle, son état actif, la validité des informations fournies et l'absence de chevauchement avec une réservation confirmée.

---

# 🎯 Fonctionnalités

## 🏫 Gestion des salles

L'application permet de :

* afficher la liste des salles ;
* afficher le détail d'une salle ;
* ajouter une salle ;
* modifier une salle ;
* activer une salle ;
* désactiver une salle.

## 📅 Gestion des réservations

L'application permet de :

* afficher la liste des réservations ;
* afficher le détail d'une réservation ;
* créer une réservation ;
* filtrer les réservations par salle ;
* annuler une réservation.

---

# 🧩 Règles métier

Lorsqu'une réservation est créée, les règles suivantes sont appliquées.

### 1. La salle doit exister

Une réservation ne peut être effectuée que pour une salle existante.

### 2. La salle doit être active

Une salle désactivée ne peut pas recevoir de nouvelle réservation.

### 3. Le responsable est obligatoire

Le nom du responsable doit être renseigné.

### 4. L'adresse email doit être valide

L'adresse email fournie doit respecter un format valide.

### 5. Le motif doit être valide

Le motif doit contenir entre **5 et 255 caractères**.

### 6. La date de début doit être avant la date de fin

```text
date_debut < date_fin
```

### 7. La durée maximale est de 4 heures

Une réservation ne peut pas dépasser quatre heures.

### 8. La réservation doit commencer dans le futur

La date de début doit être ultérieure à la date et à l'heure actuelles.

### 9. Les réservations confirmées ne doivent pas se chevaucher

Le chevauchement est déterminé avec la règle :

```text
nouvelleDateDebut < dateFinExistante

ET

nouvelleDateFin > dateDebutExistante
```

Une réservation annulée ne bloque plus la salle.

---

# 🗃️ Modèle de données

Le projet utilise deux tables principales :

```text
salles
   │
   │ 1
   │
   │ N
   ▼
reservations
```

Une salle peut avoir plusieurs réservations.

Une réservation appartient à une seule salle.

---

## 🏫 Table `salles`

Structure :

```text
id
nom
batiment
capacite
type
active
created_at
updated_at
```

Les types de salles autorisés sont :

```text
cours
informatique
laboratoire
amphitheatre
reunion
```

Le champ `active` permet de déterminer si une salle peut être réservée.

---

## 📅 Table `reservations`

Structure :

```text
id
salle_id
responsable
email
motif
date_debut
date_fin
statut
created_at
updated_at
```

Les statuts possibles sont :

```text
confirmee
annulee
```

Ces valeurs techniques sont affichées sous les libellés français **« Confirmée »** et **« Annulée »** dans l'interface.

La colonne `salle_id` est une clé étrangère vers :

```text
salles.id
```

La contrainte utilise :

```sql
ON DELETE RESTRICT
ON UPDATE CASCADE
```

---

# 🧪 Données initiales

Le projet possède un seeder situé dans :

```text
database/seed.php
```

Les données initiales comprennent les salles suivantes :

| Nom                  | Bâtiment   | Capacité | Type         | Active |
| -------------------- | ---------- | -------: | ------------ | ------ |
| Amphithéâtre A       | Bâtiment A |      250 | amphitheatre | Oui    |
| Salle B12            | Bâtiment B |       40 | cours        | Oui    |
| Laboratoire Chimie   | Bâtiment C |       24 | laboratoire  | Oui    |
| Salle Informatique 1 | Bâtiment D |       30 | informatique | Oui    |
| Salle de réunion     | Bâtiment E |       12 | reunion      | Oui    |

Le seeder utilise Eloquent et `firstOrCreate()` afin d'éviter de créer plusieurs fois une même salle.

---

# 🛠️ Technologies utilisées

* **PHP 8.3+**
* **MySQL**
* **Composer**
* **Eloquent ORM**
* **FastRoute**
* **Respect\Validation**
* **PHP-DI**
* **PHP-Dotenv**
* **PHPUnit**
* **Docker** pour l'environnement d'exécution et les workflows de build d'images

---

# 📦 Dépendances

Les dépendances principales du projet sont :

```json
{
    "nikic/fast-route": "^1.3",
    "respect/validation": "^2.4",
    "illuminate/database": "^12.0",
    "php-di/php-di": "^7.0",
    "vlucas/phpdotenv": "^5.7"
}
```

Les tests utilisent :

```json
{
    "phpunit/phpunit": "^12.5"
}
```

Les versions exactes installées sont verrouillées dans `composer.lock`.

---

# 📋 Prérequis

Pour une installation locale, il faut disposer de :

* PHP 8.3 ou supérieur ;
* Composer ;
* MySQL ;
* Git.

Pour vérifier PHP :

```bash
php --version
```

Pour vérifier Composer :

```bash
composer --version
```

Pour vérifier MySQL :

```bash
mysql --version
```

Si l'application est exécutée avec Docker, Docker Engine et Docker Compose doivent également être disponibles.

Vérifier Docker :

```bash
docker --version
```

Vérifier Docker Compose :

```bash
docker compose version
```

---

# 🚀 Installation

## 1. Récupérer le projet

Cloner le dépôt :

```bash
git clone <URL_DU_DEPOT>
```

Puis accéder au projet :

```bash
cd Gestion_University
```

---

## 2. Installer les dépendances

```bash
composer install
```

Cette commande installe les dépendances définies dans `composer.json` et verrouillées dans `composer.lock`.

---

# 🗄️ Configuration MySQL

Créer la base de données :

```sql
CREATE DATABASE gestion_university
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Se connecter à MySQL :

```bash
mysql -u root -p
```

Puis sélectionner la base :

```sql
USE gestion_university;
```

---

# ⚙️ Configuration de l'environnement

Le projet utilise **PHP-Dotenv** pour charger la configuration depuis `.env`.

Créer le fichier `.env` à partir du modèle :

```bash
cp .env.example .env
```

Puis renseigner les paramètres de connexion à MySQL :

```env
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_university
DB_USERNAME=root
DB_PASSWORD=
```

> Le fichier `.env` contient les informations sensibles et ne doit pas être versionné.

Le fichier `.env.example` sert uniquement de modèle de configuration.

---

# 🏗️ Création des tables

Les scripts SQL se trouvent dans :

```text
database/migrations/

├── 001_create_salles.sql
└── 002_create_reservations.sql
```

Ces fichiers sont des **scripts SQL de création de tables** exécutés manuellement.

La création de `salles` doit être effectuée avant celle de `reservations`, car `reservations.salle_id` référence `salles.id`.

## Créer `salles`

Depuis MySQL :

```sql
SOURCE database/migrations/001_create_salles.sql;
```

## Créer `reservations`

```sql
SOURCE database/migrations/002_create_reservations.sql;
```

---

# 🌱 Exécuter le seeder

Une fois les tables créées et la configuration `.env` terminée :

```bash
php database/seed.php
```

Le programme utilise Eloquent et `firstOrCreate()` afin d'éviter les doublons.

Lorsque l'opération est terminée, le programme affiche :

```text
Seeder terminé.
```

---

# 🐳 Exécution avec Docker

Le projet dispose également d'une configuration Docker permettant d'exécuter l'application dans un environnement conteneurisé.

Pour démarrer les services :

```bash
docker compose up -d
```

Vérifier leur état :

```bash
docker compose ps
```

Pour consulter les logs de l'application :

```bash
docker compose logs app
```

Pour arrêter les services :

```bash
docker compose down
```

La configuration Docker dépend des fichiers `Dockerfile` et `compose.yaml` présents dans le dépôt.

---

# ▶️ Lancer l'application

Le **Front Controller** de l'application est :

```text
public/index.php
```

En environnement local avec le serveur PHP intégré :

```bash
php -S localhost:8000 -t public
```

Puis accéder à :

```text
http://localhost:8000
```

---

# 🧭 Routage

Le routage est géré par **FastRoute**.

Les routes sont définies dans :

```text
routes/web.php
```

## Routes principales

### 🏫 Salles

```text
GET  /
GET  /salles
GET  /salles/create
POST /salles
GET  /salles/{id}
GET  /salles/{id}/edit
POST /salles/{id}/edit
```

### 📅 Réservations

```text
GET  /reservations
GET  /reservations/create
POST /reservations
GET  /reservations/{id}
POST /reservations/{id}/cancel
```

La liste des réservations peut être filtrée par salle avec le paramètre `salle_id` :

```text
GET /reservations?salle_id=2
```

Une URL inconnue retourne :

```text
404 Not Found
```

Une méthode HTTP non autorisée retourne :

```text
405 Method Not Allowed
```

avec l'en-tête HTTP :

```text
Allow
```

contenant les méthodes autorisées par la route.

---

# 🏛️ Architecture

Le projet est organisé en plusieurs couches afin de séparer les responsabilités.

```text
Utilisateur
     │
     ▼
public/index.php
     │
     ▼
FastRoute
     │
     ▼
Controller
     │
     ├──────────────► Validator
     │
     ├──────────────► DTO
     │
     ▼
Service
     │
     ▼
Repository
     │
     ▼
Eloquent ORM
     │
     ▼
MySQL
```

Cette organisation permet de séparer :

* la réception des requêtes HTTP ;
* la validation des données ;
* le transport des données ;
* les règles métier ;
* l'accès aux données ;
* la persistance en base de données.

---

# 📁 Structure du projet

```text
Gestion_University/

│
├── config/
│   ├── container.php
│   └── database.php
│
├── database/
│   ├── migrations/
│   │   ├── 001_create_salles.sql
│   │   └── 002_create_reservations.sql
│   └── seed.php
│
├── public/
│   ├── assets/
│   │   └── style.css
│   └── index.php
│
├── routes/
│   └── web.php
│
├── src/
│   ├── Controller/
│   ├── DTO/
│   ├── Exception/
│   ├── Model/
│   ├── Repository/
│   ├── Service/
│   └── Validation/
│
├── templates/
│   ├── error/
│   ├── layout/
│   ├── reservation/
│   └── salle/
│
├── tests/
│   ├── Unit/
│   └── Integration/
│
├── .env.example
├── .gitignore
├── CHANGELOG.md
├── composer.json
├── composer.lock
├── phpunit.xml
├── README.md
└── ARCHITECTURE.md
```

---

# 🧱 Organisation des responsabilités

## Controller

Les contrôleurs reçoivent les données HTTP et coordonnent les différentes couches.

Exemples :

```text
SalleController
ReservationController
```

Les contrôleurs ne doivent pas contenir directement les requêtes ORM liées à la base de données.

---

## Validation

La validation des données HTTP est réalisée avant leur utilisation.

Exemples :

```text
SalleValidator
ReservationValidator
```

`ReservationValidator` vérifie notamment :

* la salle ;
* le responsable ;
* l'adresse email ;
* le motif ;
* la date de début ;
* la date de fin.

La validation concerne principalement les données entrantes.

Les règles métier plus complexes sont appliquées par les services.

---

## DTO

Les DTO transportent les données nécessaires aux opérations métier.

```text
CreerSalleDTO
CreerReservationDTO
```

Ils permettent de transmettre des données structurées entre les différentes couches de l'application.

Le flux général est :

```text
Données HTTP
    │
    ▼
Validator
    │
    ▼
DTO
    │
    ▼
Service
```

---

## Service

Les services contiennent les règles métier.

```text
CreerSalleService
CreerReservationService
AnnulerReservationService
```

Par exemple, `CreerReservationService` vérifie :

* l'existence de la salle ;
* son activation ;
* l'ordre des dates ;
* la durée maximale ;
* le caractère futur de la réservation ;
* les conflits avec les réservations confirmées.

---

## Repository

Les repositories encapsulent l'accès aux modèles.

```text
SalleRepository
ReservationRepository
```

Ils permettent notamment de :

* lister ;
* rechercher ;
* enregistrer ;
* annuler ;
* rechercher un conflit.

Les interfaces de repository permettent aux services et contrôleurs de dépendre d'abstractions plutôt que directement des implémentations concrètes.

---

## Model

Les modèles Eloquent représentent les données de l'application.

```text
Salle
Reservation
```

Le modèle `Salle` possède une relation `hasMany` avec les réservations.

Le modèle `Reservation` possède une relation `belongsTo` avec une salle.

---

# 💉 Injection de dépendances

Le projet utilise **PHP-DI**.

Le conteneur est configuré dans :

```text
config/container.php
```

Les dépendances sont injectées par constructeur.

Exemple :

```php
public function __construct(
    SalleRepositoryInterface $salleRepository,
    CreerSalleService $creerSalleService,
    SalleValidator $salleValidator
) {
    // ...
}
```

Cette organisation évite que les contrôleurs créent eux-mêmes leurs dépendances.

Le conteneur permet notamment l'autowiring et la résolution des dépendances.

---

# 🧪 Scénarios de recette

Les scénarios suivants servent à vérifier les principales règles fonctionnelles de l'application.

> Les dates utilisées ci-dessous constituent des données de test et peuvent être adaptées à la date d'exécution des tests.

## Scénario 1 — Réservation valide

Données utilisées :

```text
Salle : Salle B12
Date : 08/09/2026
Horaire : 10h00 → 12h00
Responsable : Awa Ndiaye
Email : awa.ndiaye@universite.sn
Motif : Cours d'architecture logicielle
```

Résultat attendu :

```text
Réservation confirmée
```

---

## Scénario 2 — Chevauchement

Réservation existante :

```text
10h00 → 12h00
```

Nouvelle réservation :

```text
11h30 → 13h00
```

Résultat attendu :

```text
Réservation refusée
```

---

## Scénario 3 — Réservations voisines

Réservation existante :

```text
10h00 → 12h00
```

Nouvelle réservation :

```text
12h00 → 14h00
```

Résultat attendu :

```text
Réservation acceptée
```

---

## Scénario 4 — Salle inactive

La salle :

```text
Laboratoire Chimie
```

est désactivée.

Résultat attendu :

```text
Réservation refusée
```

---

## Scénario 5 — Durée supérieure à 4 heures

Exemple :

```text
08h00 → 14h00
```

Durée :

```text
6 heures
```

Résultat attendu :

```text
Réservation refusée
```

---

## Scénario 6 — Formulaire invalide

Données invalides :

```text
Responsable : vide
Email : invalide
Motif : TP
```

Résultat attendu :

```text
Erreurs de validation affichées
Aucune réservation invalide insérée
```

---

## Scénario 7 — URL inconnue

Exemple :

```text
GET /inconnue
```

Résultat attendu :

```text
404 Not Found
```

---

## Scénario 8 — Méthode HTTP non autorisée

Exemple :

```text
DELETE /salles
```

Résultat attendu :

```text
405 Method Not Allowed
```

avec un en-tête `Allow` indiquant les méthodes autorisées.

---

# 🧪 Tests PHPUnit

Les tests automatisés sont organisés dans :

```text
tests/

├── Unit/
└── Integration/
```

La commande prévue pour exécuter PHPUnit est :

```bash
vendor/bin/phpunit
```

Les tests unitaires vérifient notamment les validateurs et les règles métier isolables.

Les tests d'intégration vérifient notamment les interactions avec Eloquent, les relations, les conflits de réservation et l'annulation.

---

# 🔐 Sécurité et bonnes pratiques

Le projet applique plusieurs principes :

* les secrets sont placés dans `.env` ;
* `.env` ne doit pas être versionné ;
* `.env.example` documente la configuration nécessaire ;
* les données HTTP sont validées avant utilisation ;
* les règles métier sont centralisées dans les services ;
* les accès aux données sont encapsulés dans les repositories ;
* les dépendances sont injectées par constructeur ;
* `public/index.php` constitue le point d'entrée de l'application ;
* les requêtes vers la base de données utilisent Eloquent ;
* les responsabilités sont séparées entre les différentes couches.

---

# 🌿 Git et versions

Le projet utilise Git pour suivre les différentes étapes du développement.

Les versions principales sont marquées par les tags :

```text
v0.0.0
v0.1.0
v0.2.0
v0.3.0
v0.4.0
v0.5.0
v0.6.0
v0.7.0
v0.8.0
v0.9.0
v0.10.0
```

Le routing avec FastRoute correspond au tag :

```text
v0.10.0
```

Un correctif concernant la validation des données provenant du formulaire a ensuite été enregistré avec le commit :

```text
b5966be

fix: adapt reservation validation to form input
```

---

# 🐳 Images Docker et releases

Le projet possède également un workflow permettant de construire des images Docker à partir des tags Git.

Le workflow est situé dans :

```text
.github/workflows/docker-tag-image.yml
```

Lorsqu'une release/tag est traité par ce workflow, une image Docker peut être construite à partir du contenu correspondant au tag et publiée dans un registre de conteneurs configuré par le projet.

Le script local de génération des images pour les tags est :

```text
docker/docker-release-all.sh
```

Exemple :

```bash
IMAGE=papamamadoudiouf/gestion-university \
./docker/docker-release-all.sh
```

Avant d'utiliser le script avec un registre nécessitant une authentification, se connecter au registre concerné avec :

```bash
docker login
```

La variable `IMAGE` permet de définir le nom du dépôt d'images utilisé par le script.

Les noms exacts des registres et des images dépendent de la configuration du workflow et du script.

---

# 📚 Documentation complémentaire

L'analyse détaillée de l'architecture du projet est documentée dans :

```text
ARCHITECTURE.md
```

Cette documentation présente notamment :

* MVC ;
* Front Controller ;
* Router ;
* Validator ;
* DTO ;
* ORM ;
* Active Record ;
* Repository ;
* Service ;
* injection par constructeur ;
* conteneur DI ;
* autowiring ;
* IoC ;
* principes SOLID.

---

# 👨‍💻 Auteur

**Leprogrammeur**

Projet individuel — Gestion et réservation de salles universitaires.

```

Cette version est maintenant **plus cohérente et plus prudente** : elle ne prétend pas que les tags `v0.x` et `v1.0.0` représentent la même release, ne présente pas les scripts SQL comme de vraies migrations framework, et distingue l'exécution locale de l'exécution Docker.
```
