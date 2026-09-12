# Changelog

Toutes les modifications importantes du projet **Gestion University** sont documentées dans ce fichier.

Le format de ce fichier s'inspire de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/).

---

## [Unreleased]

Cette section contient les modifications réalisées depuis la dernière version publiée et qui ne sont pas encore associées à une nouvelle version.

### Correctifs

* Correction de la validation des données du formulaire de création d'une réservation.
* Adaptation de la validation de `salle_id` au format réellement envoyé par le formulaire HTML (`<select>`).
* Adaptation de la validation des champs `date_debut` et `date_fin` au format produit par `<input type="datetime-local">`.
* Conservation des données saisies lors du retour au formulaire en cas d'erreur de validation.
* Gestion HTTP des exceptions métier lors de la création et de l'annulation d'une réservation.
* Affichage des erreurs métier dans les formulaires concernés.
* Retour HTTP `404` lorsqu'une réservation demandée n'existe pas.
* Ajout du filtrage des réservations par salle.
* Ajout des messages de succès après certaines opérations.

### Scénarios de recette vérifiés

Les scénarios suivants ont été vérifiés dans l'application :

* Création d'une réservation valide.
* Détection d'un chevauchement de réservation.
* Autorisation de deux réservations consécutives sans chevauchement.
* Refus d'une réservation sur une salle inactive.
* Refus d'une réservation dont la durée dépasse quatre heures.
* Validation d'un formulaire contenant plusieurs erreurs simultanées.
* Affichage d'une erreur `404` pour une URL inconnue.
* Affichage d'une erreur `405` pour une méthode HTTP non autorisée.
* Présence de l'en-tête HTTP `Allow` lors d'une réponse `405`.

---

# [0.10.0] - Routing avec FastRoute

### Ajouté

* Mise en place du routage avec `nikic/fast-route`.
* Création du fichier `routes/web.php`.
* Définition des routes de l'application dans un fichier séparé.
* Mise en place du Front Controller dans `public/index.php`.
* Gestion des routes dynamiques avec identifiants numériques.
* Gestion des méthodes HTTP `GET` et `POST`.
* Gestion des routes inconnues avec une réponse HTTP `404`.
* Gestion des méthodes HTTP non autorisées avec une réponse HTTP `405`.
* Ajout de l'en-tête HTTP `Allow` pour les réponses `405`.
* Connexion du routeur au conteneur PHP-DI.
* Résolution des contrôleurs via le conteneur de dépendances.

### Routes principales

* `/`
* `/salles`
* `/salles/create`
* `/salles/{id}`
* `/salles/{id}/edit`
* `/reservations`
* `/reservations/create`
* `/reservations/{id}`
* `/reservations/{id}/cancel`

### Vérifications

* Vérification des routes existantes.
* Vérification des routes dynamiques.
* Vérification des routes inconnues.
* Vérification des méthodes HTTP non autorisées.
* Vérification de l'en-tête `Allow`.

---

# [0.9.0] - Interface Web

### Ajouté

* Mise en place de l'interface Web de l'application.
* Création des templates pour les salles.
* Création des templates pour les réservations.
* Mise en place d'un layout commun.
* Création des pages d'erreur `404` et `405`.
* Ajout de la feuille de style principale.
* Mise en place des formulaires de création et de modification.
* Affichage de la liste des salles.
* Affichage de la liste des réservations.
* Affichage du détail d'une salle.
* Affichage du détail d'une réservation.
* Formulaire de création d'une réservation.
* Formulaire de création d'une salle.
* Formulaire de modification d'une salle.
* Formulaire d'annulation d'une réservation.

### Structure concernée

```text
templates/

├── error/
│   ├── 404.php
│   └── 405.php
│
├── layout/
│   └── base.php
│
├── reservation/
│   ├── form.php
│   ├── index.php
│   └── show.php
│
└── salle/
    ├── form.php
    ├── index.php
    └── show.php

public/

└── assets/
    └── style.css
```

---

# [0.8.0] - Services et exceptions métier

### Ajouté

* Mise en place de la couche Service.
* Création de `CreerSalleService`.
* Création de `CreerReservationService`.
* Création de `AnnulerReservationService`.
* Déplacement des règles métier de réservation dans les Services.
* Vérification de l'existence d'une salle.
* Vérification de l'état actif d'une salle.
* Vérification de l'ordre des dates.
* Vérification de la durée maximale de quatre heures.
* Vérification que la réservation commence dans le futur.
* Vérification des conflits de réservation.
* Prise en compte uniquement des réservations confirmées lors de la recherche de conflits.
* Création de `SalleIndisponibleException`.
* Création de `ReservationIntrouvableException`.

### Règle de chevauchement

La détection des conflits utilise la règle :

```text
nouveau début < fin existante

ET

nouvelle fin > début existant
```

Cette règle autorise deux réservations consécutives :

```text
10h00 ───────── 12h00
                    12h00 ───────── 14h00
```

La deuxième réservation peut donc commencer exactement lorsque la première se termine.

---

# [0.7.0] - Repositories

### Ajouté

* Mise en place de la couche Repository.
* Création de `SalleRepositoryInterface`.
* Création de `SalleRepository`.
* Création de `ReservationRepositoryInterface`.
* Création de `ReservationRepository`.
* Encapsulation des opérations d'accès aux données.
* Méthode de récupération de la liste des salles.
* Méthode de récupération d'une salle par son identifiant.
* Méthode de récupération de la liste des réservations.
* Méthode de récupération d'une réservation par son identifiant.
* Méthode de recherche des conflits de réservation.
* Méthode d'enregistrement d'une salle.
* Méthode d'enregistrement d'une réservation.
* Méthode permettant de modifier le statut d'une réservation lors de son annulation.

### Architecture

Les contrôleurs n'effectuent pas directement les opérations d'accès aux données.

Le flux adopté est :

```text
Controller
    ↓
Service
    ↓
Repository
    ↓
Model Eloquent
    ↓
Eloquent ORM
    ↓
MySQL
```

Le Service porte la décision métier.

Le Repository encapsule l'accès aux données nécessaire à cette opération.

---

# [0.6.0] - DTO

### Ajouté

* Mise en place des Data Transfer Objects.
* Création de `CreerSalleDTO`.
* Création de `CreerReservationDTO`.
* Utilisation des DTO pour transporter les données validées vers les Services.
* Utilisation de `DateTimeImmutable` dans `CreerReservationDTO`.

### DTO créés

```text
App\DTO\CreerSalleDTO
App\DTO\CreerReservationDTO
```

### Flux

```text
Données HTTP
    ↓
Validator
    ↓
Données validées
    ↓
DTO
    ↓
Service
```

---

# [0.5.0] - Validation

### Ajouté

* Mise en place de la couche Validation.
* Création de `ValidatorInterface`.
* Création de `ValidationResult`.
* Création de `SalleValidator`.
* Création de `ReservationValidator`.
* Utilisation de `respect/validation`.
* Validation des données avant leur transformation en DTO.
* Validation du nom du responsable.
* Validation de l'adresse email.
* Validation du motif.
* Validation de l'identifiant de salle.
* Validation des dates de réservation.

### Règles de validation

* Responsable : entre 2 et 120 caractères.
* Email : adresse email valide.
* Motif : entre 5 et 255 caractères.
* Identifiant de salle : valeur numérique valide.
* Dates : format compatible avec les données envoyées par le formulaire HTML.

Le Validator est responsable de la validation des données entrantes.

Les règles métier sont quant à elles appliquées dans les Services.

---

# [0.4.0] - Données initiales

### Ajouté

* Création du script `database/seed.php`.
* Ajout des données initiales des salles.
* Utilisation d'Eloquent pour l'insertion des données.
* Utilisation de `firstOrCreate()` afin d'éviter les doublons lors du seed.

### Salles initiales

| Salle                | Bâtiment   | Capacité | Type         |
| -------------------- | ---------- | -------: | ------------ |
| Amphithéâtre A       | Bâtiment A |      250 | amphitheatre |
| Salle B12            | Bâtiment B |       40 | cours        |
| Laboratoire Chimie   | Bâtiment C |       24 | laboratoire  |
| Salle Informatique 1 | Bâtiment D |       30 | informatique |
| Salle de réunion     | Bâtiment E |       12 | reunion      |

---

# [0.3.0] - Modèles Eloquent

### Ajouté

* Création du modèle `Salle`.
* Création du modèle `Reservation`.
* Mise en place des relations Eloquent.
* Relation entre une salle et ses réservations.
* Configuration des attributs remplissables.
* Configuration des casts Eloquent.
* Mise en place des Models conformément à la structure de la base de données.

### Relations

```text
Salle
  │
  └── hasMany
          │
          ▼
    Reservation
```

Les classes `Salle` et `Reservation` sont des Models Eloquent représentant les données de l'application.

---

# [0.2.0] - Base de données et Eloquent

### Ajouté

* Mise en place de la configuration de la base de données.
* Utilisation de `vlucas/phpdotenv`.
* Configuration des variables d'environnement avec `.env`.
* Mise en place d'Eloquent ORM.
* Configuration de `Illuminate\Database\Capsule\Manager`.
* Initialisation d'Eloquent.
* Connexion à MySQL.
* Configuration du charset `utf8mb4`.
* Configuration de la collation `utf8mb4_unicode_ci`.

### Sécurité

* Les informations de connexion à la base de données sont stockées dans `.env`.
* Les secrets ne doivent pas être versionnés dans Git.
* Le fichier `.env.example` fournit un modèle de configuration.

---

# [0.1.0] - Initialisation technique

### Ajouté

* Initialisation de Composer.
* Configuration de l'autoloading PSR-4.
* Installation des dépendances principales.
* Mise en place de l'arborescence du projet.
* Préparation de la structure MVC.
* Préparation des différentes couches de l'application.

### Dépendances principales

* `nikic/fast-route`
* `respect/validation`
* `illuminate/database`
* `php-di/php-di`
* `vlucas/phpdotenv`

### Dépendance de développement

* `phpunit/phpunit`

---

# [0.0.0] - Initialisation du projet

### Ajouté

* Initialisation du dépôt Git.
* Création de la branche principale.
* Mise en place du fichier `.gitignore`.
* Création des premiers éléments de documentation du projet.
* Création du fichier `README.md`.
* Préparation du suivi des versions avec des tags Git.

---

# Historique des versions

Les principales étapes du développement sont organisées comme suit :

```text
v0.0.0  → Initialisation du projet

v0.1.0  → Composer / autoload / dépendances

v0.2.0  → Eloquent / .env / base de données

v0.3.0  → Models et relations Eloquent

v0.4.0  → Données initiales / seed

v0.5.0  → Validation

v0.6.0  → DTO

v0.7.0  → Repositories

v0.8.0  → Services et exceptions métier

v0.9.0  → Interface Web

v0.10.0 → Routing avec FastRoute
```

---

# Commits récents importants

```text
b5966be fix: adapt reservation validation to form input

4276dc8 feat: implement routing with FastRoute

d7d1ddf feat: implement web interface

dbfabad feat: implement reservation business services

6ec2816 feat: ajouter les repositories

879b5e0 feat: ajouter les DTO

139461f feat: ajouter la validation

824152c feat: ajouter les données initiales

5934d14 feat: créer les modèles Salle et Reservation

32e04bb chore: configurer l'autoloading App
```

---

# Tags Git

Les versions actuellement identifiées sont :

```text
v0.3.0
v0.4.0
v0.5.0
v0.6.0
v0.7.0
v0.8.0
v0.9.0
v0.10.0
```

---

# Convention de version

Le projet suit une convention inspirée de **Semantic Versioning**.

Le format général est :

```text
MAJOR.MINOR.PATCH
```

Dans la phase actuelle de développement :

```text
0.MINOR.PATCH
```

est utilisé pour représenter les étapes de construction du projet.

### Version MINOR

Une version `MINOR` correspond à l'ajout d'une fonctionnalité ou d'une étape architecturale importante.

Exemple :

```text
v0.9.0
   ↑
nouvelle fonctionnalité importante
```

### Version PATCH

Une version `PATCH` correspond principalement à une correction d'un comportement existant sans modification majeure de l'architecture.

Exemple :

```text
v0.10.1
       ↑
correction
```

### Section Unreleased

Les modifications réalisées après la dernière version sont d'abord placées dans :

```text
[Unreleased]
```

Lorsqu'une étape stable est terminée, elles sont regroupées dans une nouvelle version et un nouveau tag Git peut être créé.

---

# Principe de cohérence

Le `CHANGELOG.md` doit toujours refléter l'état réel du projet.

Une modification ne doit être ajoutée au changelog comme étant terminée que si elle a réellement été implémentée.

De même, un scénario ne doit être indiqué comme « vérifié » que s'il a effectivement été testé.

Le changelog constitue ainsi l'historique fonctionnel et technique du projet.
