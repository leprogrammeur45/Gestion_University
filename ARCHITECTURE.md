# Architecture du projet — Gestion University

## 1. Présentation générale

**Gestion University** est une application Web développée en PHP orienté objet permettant de gérer les salles d'une université ainsi que leurs réservations.

L'application permet notamment de :

* consulter les salles ;
* consulter le détail d'une salle ;
* créer une salle ;
* modifier une salle ;
* consulter les réservations ;
* consulter le détail d'une réservation ;
* créer une réservation ;
* annuler une réservation ;
* vérifier la disponibilité d'une salle ;
* empêcher les chevauchements de réservations ;
* empêcher la réservation d'une salle inactive ;
* contrôler la durée et les dates d'une réservation.

L'application utilise une architecture en plusieurs couches afin de séparer clairement les responsabilités.

---

# 2. Architecture globale

L'architecture générale est la suivante :

```text
                         Navigateur
                              │
                              ▼
                     public/index.php
                     Front Controller
                              │
                              ▼
                         FastRoute
                           Router
                              │
                              ▼
                        Controller
                              │
              ┌───────────────┼────────────────┐
              │               │                │
              ▼               ▼                ▼
          Validator          DTO             View
              │
              ▼
           Service
              │
              ▼
         Repository
              │
              ▼
        Model Eloquent
              │
              ▼
            MySQL
```

Chaque couche possède une responsabilité précise.

L'objectif est d'éviter qu'un seul composant contienne simultanément :

```text
HTTP
+
validation
+
logique métier
+
SQL
+
affichage
```

---

# 3. Organisation du projet

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
├── .env
├── .env.example
├── composer.json
├── composer.lock
├── phpunit.xml
├── README.md
├── CHANGELOG.md
└── ARCHITECTURE.md
```

---

# 4. MVC

## 4.1 Définition

MVC signifie :

```text
M = Model
V = View
C = Controller
```

MVC sépare principalement :

* les données ;
* l'affichage ;
* la gestion des requêtes HTTP.

---

## 4.2 Model

Le Model représente les données manipulées par l'application.

Dans notre projet :

```text
src/Model/

├── Salle.php
└── Reservation.php
```

Ces classes sont des **Models Eloquent**.

Elles représentent notamment les tables :

```text
salles
reservations
```

---

## 4.3 View

La View est responsable de la présentation HTML.

Dans notre projet :

```text
templates/

├── layout/
├── salle/
├── reservation/
└── error/
```

Une View ne doit pas contenir de logique métier.

---

## 4.4 Controller

Le Controller reçoit la requête HTTP et coordonne les différentes couches.

Exemple :

```text
src/Controller/

├── SalleController.php
└── ReservationController.php
```

Le Controller :

* récupère les données HTTP ;
* appelle le Validator ;
* construit le DTO ;
* appelle le Service ;
* prépare les données pour la View ;
* effectue les redirections HTTP.

Il ne doit pas contenir les règles métier complexes.

---

# 5. Front Controller

## 5.1 Définition

Le Front Controller est le point d'entrée unique de l'application.

Dans notre projet :

```text
public/index.php
```

Toutes les requêtes HTTP passent par ce fichier.

---

## 5.2 Responsabilités

Le Front Controller initialise notamment :

```text
Composer
    ↓
Configuration
    ↓
Eloquent
    ↓
Container DI
    ↓
Router
```

Puis il transmet la requête au routeur.

---

## 5.3 Exemple

```php
$dispatcher = simpleDispatcher(
    require __DIR__ . '/../routes/web.php'
);

$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
```

Le Front Controller ne doit pas contenir la logique métier.

---

# 6. Router

## 6.1 Définition

Le Router détermine quelle action doit être exécutée pour une URL donnée.

Notre application utilise :

```text
nikic/fast-route
```

Les routes sont définies dans :

```text
routes/web.php
```

---

## 6.2 Exemple

```php
$r->addRoute(
    'GET',
    '/salles',
    [SalleController::class, 'index']
);
```

Cela signifie :

```text
GET /salles
      ↓
SalleController::index()
```

---

## 6.3 Route dynamique

```php
$r->addRoute(
    'GET',
    '/salles/{id:\d+}',
    [SalleController::class, 'show']
);
```

Ainsi :

```text
GET /salles/2
```

permet de récupérer :

```text
id = 2
```

puis d'appeler :

```php
SalleController::show(2);
```

---

## 6.4 Erreurs HTTP

Le Router permet également de gérer :

```text
404 Not Found
405 Method Not Allowed
```

Pour une méthode HTTP interdite, l'application peut également retourner :

```http
Allow: GET, POST
```

---

# 7. Validator

## 7.1 Définition

Le Validator vérifie que les données reçues respectent les contraintes de validation.

Notre couche :

```text
src/Validation/
```

contient notamment :

```text
ValidatorInterface
ValidationResult
SalleValidator
ReservationValidator
```

---

## 7.2 Responsabilité

Le Validator vérifie les données entrantes.

Exemple :

```text
nom renseigné ?
capacite valide ?
email valide ?
date correctement formatée ?
motif suffisamment long ?
```

Il ne doit pas décider si une réservation est autorisée selon les règles métier.

---

# 8. Validation des données et validation métier

Il faut distinguer deux niveaux.

## 8.1 Validation des données

Elle appartient au Validator.

Exemples :

```text
email valide
nom non vide
motif entre 5 et 255 caractères
date correctement formatée
capacite numérique
```

---

## 8.2 Validation métier

Elle appartient au Service.

Exemples :

```text
la salle existe-t-elle ?
la salle est-elle active ?
la date de début est-elle dans le futur ?
la durée dépasse-t-elle quatre heures ?
la salle est-elle déjà réservée ?
```

Cette séparation est essentielle.

---

# 9. DTO

## 9.1 Définition

DTO signifie :

```text
Data Transfer Object
```

Un DTO transporte des données structurées et typées entre différentes couches.

---

## 9.2 DTO de création d'une salle

```text
App\DTO\CreerSalleDTO
```

Il contient notamment :

```php
public readonly string $nom;

public readonly string $batiment;

public readonly int $capacite;

public readonly string $type;

public readonly bool $active;
```

---

## 9.3 DTO de création d'une réservation

```text
App\DTO\CreerReservationDTO
```

Il contient notamment :

```php
public readonly int $salleId;

public readonly string $responsable;

public readonly string $email;

public readonly string $motif;

public readonly DateTimeImmutable $dateDebut;

public readonly DateTimeImmutable $dateFin;
```

---

## 9.4 Flux Validator → DTO

Le flux correct est :

```text
$_POST
   ↓
Validator
   ↓
Données valides
   ↓
DTO
   ↓
Service
```

Le Validator valide les données.

Le Controller transforme ensuite les données validées en DTO.

Le Service reçoit le DTO.

---

# 10. Service

## 10.1 Définition

Le Service contient la logique métier.

Notre couche :

```text
src/Service/
```

contient notamment :

```text
CreerSalleService
CreerReservationService
AnnulerReservationService
```

---

## 10.2 Responsabilités

Le Service :

* applique les règles métier ;
* vérifie les conditions nécessaires ;
* coordonne les repositories ;
* décide si l'opération métier est autorisée.

Le Service ne doit pas gérer l'affichage HTML.

---

# 11. Exemple : création d'une réservation

Le Service :

```text
CreerReservationService
```

effectue les vérifications métier.

---

## 11.1 Vérification de la salle

```php
$salle = $this->salleRepository->retrouver($dto->salleId);

if ($salle === null || !$salle->active) {
    throw new SalleIndisponibleException(
        'La salle demandée est inexistante ou inactive.'
    );
}
```

---

## 11.2 Vérification des dates

```php
if ($dto->dateDebut >= $dto->dateFin) {
    throw new InvalidArgumentException(
        'La date de début doit précéder la date de fin.'
    );
}
```

---

## 11.3 Vérification de la durée

```php
$duree = $dto->dateFin->getTimestamp()
    - $dto->dateDebut->getTimestamp();

if ($duree > 4 * 60 * 60) {
    throw new InvalidArgumentException(
        'La durée de réservation ne peut pas dépasser quatre heures.'
    );
}
```

---

## 11.4 Vérification du futur

```php
if ($dto->dateDebut <= new DateTimeImmutable()) {
    throw new InvalidArgumentException(
        'La date de début doit être dans le futur.'
    );
}
```

---

## 11.5 Vérification du conflit

```php
$conflit = $this->reservationRepository->rechercherConflit(
    $dto->salleId,
    $dto->dateDebut,
    $dto->dateFin
);

if ($conflit !== null) {
    throw new SalleIndisponibleException(
        'La salle est déjà réservée sur cette période.'
    );
}
```

---

## 11.6 Résultat

Une réservation est autorisée uniquement si :

```text
Salle existe
     +
Salle active
     +
Données valides
     +
Dates cohérentes
     +
Durée ≤ 4 heures
     +
Début dans le futur
     +
Aucun chevauchement
     =
Réservation autorisée
```

---

# 12. Repository

## 12.1 Définition

Le Repository encapsule l'accès aux données.

Notre couche :

```text
src/Repository/
```

contient :

```text
SalleRepositoryInterface
SalleRepository

ReservationRepositoryInterface
ReservationRepository
```

---

## 12.2 Exemple

```php
public function retrouver(int $id): ?Salle
{
    return Salle::find($id);
}
```

Le Controller et le Service n'ont donc pas besoin de connaître directement la manière dont les données sont récupérées.

---

## 12.3 Recherche d'un conflit

La recherche du conflit appartient au Repository car il s'agit d'une opération d'accès aux données.

```php
return Reservation::query()
    ->where('salle_id', $salleId)
    ->where('statut', 'confirmee')
    ->where('date_debut', '<', $dateFin)
    ->where('date_fin', '>', $dateDebut)
    ->first();
```

Le Repository sait **comment rechercher** le conflit.

Le Service sait **pourquoi cette recherche est nécessaire**.

---

## 12.4 Annulation

L'annulation doit être séparée en deux responsabilités.

Le Service contient la décision métier :

```text
Est-ce que cette réservation peut être annulée ?
```

Le Repository réalise ensuite l'accès aux données :

```text
Modifier le statut
```

Le flux devient :

```text
Controller
    ↓
AnnulerReservationService
    ↓
ReservationRepository
    ↓
Reservation
    ↓
MySQL
```

---

# 13. ORM

## 13.1 Définition

ORM signifie :

```text
Object-Relational Mapping
```

Notre ORM est :

```text
Eloquent
```

fourni par :

```text
illuminate/database
```

---

## 13.2 Sans ORM

Sans ORM :

```php
SELECT * FROM salles WHERE id = ?
```

Il faudrait ensuite transformer manuellement le résultat SQL en objet PHP.

---

## 13.3 Avec Eloquent

Avec Eloquent :

```php
$salle = Salle::find($id);
```

Eloquent s'occupe de construire la requête et de transformer le résultat en Model Eloquent.

---

# 14. Model Eloquent

Dans notre architecture :

```text
src/Model/

Salle.php
Reservation.php
```

sont des Models Eloquent.

Ils représentent les données et utilisent les mécanismes de persistance fournis par Eloquent.

Le modèle peut par exemple effectuer :

```php
$salle->save();
```

ou :

```php
$salle = Salle::find($id);
```

---

# 15. Active Record

Eloquent utilise principalement le pattern :

```text
Active Record
```

Un Model Eloquent représente une ligne ou un ensemble de données et possède également des mécanismes permettant leur persistance.

Exemple :

```php
$salle = new Salle();

$salle->nom = 'Salle B20';
$salle->batiment = 'B';
$salle->capacite = 30;

$salle->save();
```

Le Model sait donc comment demander à Eloquent de sauvegarder ses données.

---

## 15.1 Limite

Active Record mélange en partie :

```text
Données
+
Persistance
```

C'est pourquoi notre architecture conserve les Services pour la logique métier et les Repositories pour encapsuler l'accès aux données.

---

# 16. Injection de dépendances

L'injection de dépendances consiste à fournir à une classe les objets dont elle dépend.

Exemple :

```php
class CreerReservationService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }
}
```

Le Service ne crée pas lui-même ses repositories.

---

## 16.1 Mauvaise approche

```php
class CreerReservationService
{
    public function executer()
    {
        $repository = new ReservationRepository();
    }
}
```

Cette approche crée un couplage direct avec l'implémentation.

---

## 16.2 Bonne approche

```php
ReservationRepositoryInterface
```

est injecté dans le Service.

Le Service dépend donc d'une abstraction.

---

# 17. Conteneur DI

DI signifie :

```text
Dependency Injection
```

Notre projet utilise :

```text
PHP-DI
```

Le conteneur est configuré dans :

```text
config/container.php
```

---

## 17.1 Correspondance interface → implémentation

Par exemple :

```text
SalleRepositoryInterface
          ↓
SalleRepository
```

et :

```text
ReservationRepositoryInterface
          ↓
ReservationRepository
```

Le conteneur sait ainsi quelle classe concrète utiliser lorsqu'une interface est demandée.

---

# 18. Autowiring

L'autowiring permet à PHP-DI d'analyser automatiquement les constructeurs et leurs types afin de construire les objets.

Exemple :

```php
class AnnulerReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }
}
```

PHP-DI peut détecter la dépendance.

Cependant, une interface ne peut pas être instanciée directement.

Il faut donc configurer :

```text
ReservationRepositoryInterface
          ↓
ReservationRepository
```

---

# 19. IoC

IoC signifie :

```text
Inversion of Control
```

L'idée est que les classes ne contrôlent pas directement la création de toutes leurs dépendances.

Le contrôle de la construction des objets est confié au conteneur.

---

## 19.1 Sans IoC

```text
Controller
    │
    ├── new Repository()
    ├── new Service()
    └── new Validator()
```

---

## 19.2 Avec IoC

```text
             Container
                 │
       ┌─────────┼─────────┐
       ▼         ▼         ▼
 Controller   Service   Repository
```

Cela permet de réduire le couplage.

---

# 20. SOLID

SOLID regroupe cinq principes :

```text
S = Single Responsibility Principle
O = Open/Closed Principle
L = Liskov Substitution Principle
I = Interface Segregation Principle
D = Dependency Inversion Principle
```

---

# 21. S — Single Responsibility Principle

Une classe doit avoir une responsabilité principale.

Dans notre projet :

```text
ReservationValidator
        ↓
Validation

CreerReservationService
        ↓
Logique métier

ReservationRepository
        ↓
Accès aux données

ReservationController
        ↓
Coordination HTTP
```

---

# 22. O — Open/Closed Principle

Une classe doit être ouverte à l'extension mais fermée à la modification.

Les interfaces permettent par exemple d'avoir plusieurs implémentations.

```php
interface SalleRepositoryInterface
{
    public function lister(): array;

    public function retrouver(int $id): ?Salle;

    public function enregistrer(Salle $salle): Salle;
}
```

Une autre implémentation pourrait respecter ce contrat sans modifier le Service.

---

# 23. L — Liskov Substitution Principle

Une implémentation doit pouvoir remplacer son abstraction sans modifier le comportement attendu.

```text
SalleRepositoryInterface
          ▲
          │
SalleRepository
```

Le Service utilise :

```php
SalleRepositoryInterface
```

et non :

```php
SalleRepository
```

---

# 24. I — Interface Segregation Principle

Il vaut mieux avoir plusieurs interfaces spécialisées qu'une énorme interface.

Dans notre projet :

```text
SalleRepositoryInterface
```

et :

```text
ReservationRepositoryInterface
```

sont séparées.

---

# 25. D — Dependency Inversion Principle

Les classes de haut niveau doivent dépendre d'abstractions.

Notre Service dépend de :

```php
ReservationRepositoryInterface
```

et non directement de :

```php
ReservationRepository
```

Exemple :

```php
public function __construct(
    private readonly ReservationRepositoryInterface $reservationRepository
) {
}
```

---

# 26. Flux complet d'une réservation

Lorsqu'un utilisateur crée une réservation :

```text
Utilisateur
    │
    │ POST /reservations
    ▼
public/index.php
    │
    ▼
FastRoute
    │
    ▼
ReservationController
    │
    ▼
ReservationValidator
    │
    ▼
Données validées
    │
    ▼
CreerReservationDTO
    │
    ▼
CreerReservationService
    │
    ▼
ReservationRepository
    │
    ▼
Reservation Model
    │
    ▼
Eloquent
    │
    ▼
MySQL
```

---

# 27. Explication détaillée du flux

## Étape 1 — Requête HTTP

Le navigateur envoie :

```text
POST /reservations
```

avec les données du formulaire.

---

## Étape 2 — Front Controller

La requête arrive dans :

```text
public/index.php
```

---

## Étape 3 — Router

FastRoute détermine l'action :

```php
[ReservationController::class, 'store']
```

---

## Étape 4 — Conteneur DI

PHP-DI construit le Controller avec ses dépendances.

---

## Étape 5 — Controller

Le Controller récupère :

```php
$_POST
```

---

## Étape 6 — Validator

Le Validator vérifie les données.

Si les données sont invalides :

```text
Controller
    ↓
View du formulaire
    ↓
Affichage des erreurs
```

Si elles sont valides :

```text
Controller
    ↓
DTO
```

---

## Étape 7 — DTO

Les données sont transformées en :

```text
CreerReservationDTO
```

Les dates deviennent par exemple :

```text
DateTimeImmutable
```

---

## Étape 8 — Service

Le DTO est transmis :

```php
$this->creerReservationService->executer($dto);
```

Le Service applique les règles métier.

---

## Étape 9 — Repository

Le Service demande au Repository :

```php
$this->reservationRepository->rechercherConflit(...);
```

---

## Étape 10 — Model Eloquent

Le Repository utilise :

```text
Reservation
```

qui est un Model Eloquent.

---

## Étape 11 — Eloquent

Eloquent construit et exécute les requêtes SQL nécessaires.

---

## Étape 12 — MySQL

MySQL stocke ou retourne les données.

---

## Étape 13 — Réponse HTTP

Après la création :

```text
Reservation créée
       ↓
redirect
       ↓
/reservations
```

---

# 28. Règles métier

La création d'une réservation respecte notamment :

```text
1. La salle doit exister.

2. La salle doit être active.

3. Le responsable doit être renseigné.

4. L'adresse email doit être valide.

5. Le motif doit contenir entre 5 et 255 caractères.

6. La date de début doit précéder la date de fin.

7. La durée ne doit pas dépasser 4 heures.

8. La date de début doit être dans le futur.

9. Il ne doit pas exister de réservation confirmée
   qui chevauche la nouvelle réservation.
```

La règle de chevauchement est :

```text
nouveau début < réservation existante fin

ET

nouveau fin > réservation existante début
```

Ainsi, une réservation annulée ne bloque plus la salle si la recherche de conflit ne considère que les réservations confirmées.

---

# 29. Gestion des exceptions

Le projet possède notamment :

```text
src/Exception/

├── ReservationIntrouvableException.php
└── SalleIndisponibleException.php
```

Exemple :

```php
throw new SalleIndisponibleException(
    'La salle est déjà réservée sur cette période.'
);
```

Les exceptions métier permettent de représenter clairement les erreurs du domaine.

Le Front Controller ou un gestionnaire d'erreurs peut ensuite transformer ces exceptions en réponses HTTP appropriées.

---

# 30. Sécurité et configuration

Les informations sensibles sont stockées dans :

```text
.env
```

et non directement dans le code.

Le projet fournit :

```text
.env.example
```

comme modèle.

Le fichier :

```text
.env
```

ne doit normalement pas être versionné dans Git.

---

# 31. Dépendances principales

| Dépendance            | Rôle                      |
| --------------------- | ------------------------- |
| `nikic/fast-route`    | Routage HTTP              |
| `respect/validation`  | Validation des données    |
| `illuminate/database` | Eloquent ORM              |
| `php-di/php-di`       | Injection de dépendances  |
| `vlucas/phpdotenv`    | Variables d'environnement |
| `phpunit/phpunit`     | Tests                     |

---

# 32. Séparation des responsabilités

## Controller

```text
HTTP
↓
Controller
```

Responsabilités :

* recevoir la requête ;
* appeler le Validator ;
* construire le DTO ;
* appeler le Service ;
* préparer la View ;
* effectuer les redirections.

---

## Validator

```text
Données HTTP
↓
Validator
```

Responsabilité :

* vérifier les données entrantes.

---

## DTO

```text
Données validées
↓
DTO
```

Responsabilité :

* transporter des données structurées et typées.

---

## Service

```text
DTO
↓
Service
```

Responsabilité :

* appliquer les règles métier.

---

## Repository

```text
Service
↓
Repository
```

Responsabilité :

* accéder aux données ;
* rechercher ;
* enregistrer ;
* modifier ;
* supprimer selon les besoins de persistance.

---

## Model

```text
Repository
↓
Model Eloquent
```

Responsabilité :

* représenter les données ;
* utiliser les mécanismes de persistance d'Eloquent.

---

## View

```text
Controller
↓
View
```

Responsabilité :

* afficher les données en HTML.

---

# 33. Architecture finale

```text
                           ┌───────────────┐
                           │   Navigateur  │
                           └───────┬───────┘
                                   │
                                   ▼
                           ┌───────────────┐
                           │ public/       │
                           │ index.php     │
                           │ Front         │
                           │ Controller    │
                           └───────┬───────┘
                                   │
                                   ▼
                           ┌───────────────┐
                           │    FastRoute   │
                           │     Router     │
                           └───────┬───────┘
                                   │
                                   ▼
                       ┌───────────────────────┐
                       │      Controller       │
                       └───────────┬───────────┘
                                   │
                    ┌──────────────┼──────────────┐
                    │              │              │
                    ▼              ▼              ▼
             ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
             │  Validator  │ │     DTO     │ │    View     │
             └──────┬──────┘ └──────┬──────┘ └─────────────┘
                    │               │
                    └───────┬───────┘
                            ▼
                     ┌─────────────┐
                     │   Service   │
                     │ Logique     │
                     │ métier      │
                     └──────┬──────┘
                            │
                            ▼
                     ┌─────────────┐
                     │ Repository  │
                     └──────┬──────┘
                            │
                            ▼
                     ┌─────────────┐
                     │    Model    │
                     │  Eloquent   │
                     └──────┬──────┘
                            │
                            ▼
                     ┌─────────────┐
                     │   Eloquent  │
                     │     ORM     │
                     └──────┬──────┘
                            │
                            ▼
                     ┌─────────────┐
                     │    MySQL    │
                     └─────────────┘
```

---

# 34. Résumé des responsabilités

| Élément            | Responsabilité principale                          |
| ------------------ | -------------------------------------------------- |
| `public/index.php` | Point d'entrée unique                              |
| FastRoute          | Routage HTTP                                       |
| Controller         | Coordination HTTP                                  |
| Validator          | Validation des données                             |
| DTO                | Transport des données                              |
| Service            | Logique métier                                     |
| Repository         | Accès aux données                                  |
| Model              | Représentation des données et persistance Eloquent |
| Eloquent           | ORM et communication avec la base                  |
| PHP-DI             | Construction et injection des dépendances          |
| View               | Affichage HTML                                     |
| MySQL              | Stockage des données                               |

---

# 35. Architecture en une phrase

On peut retenir :

```text
HTTP
 ↓
Front Controller
 ↓
Router
 ↓
Controller
 ↓
Validator
 ↓
DTO
 ↓
Service
 ↓
Repository
 ↓
Model Eloquent
 ↓
Eloquent
 ↓
MySQL
```

Avec :

```text
Controller → coordination HTTP
Validator  → validation des données
DTO        → transport des données
Service    → logique métier
Repository → accès aux données
Model      → données + persistance Eloquent
View       → affichage
PHP-DI     → injection des dépendances
```

---

# 36. Conclusion

L'architecture **Gestion University** repose sur une séparation claire des responsabilités.

Elle combine :

```text
MVC
+
Front Controller
+
Router
+
Validator
+
DTO
+
Service
+
Repository
+
ORM
+
Active Record
+
Dependency Injection
+
DI Container
+
IoC
+
SOLID
```

Le principe essentiel est :

```text
Le Controller gère HTTP.

Le Validator vérifie les données.

Le DTO transporte les données.

Le Service applique les règles métier.

Le Repository accède aux données.

Le Model représente les données avec Eloquent.

Eloquent assure la persistance ORM.

La View affiche les données.

PHP-DI construit et injecte les dépendances.
```

Cette architecture est adaptée à **Gestion University** car elle permet de garder le projet organisé, testable, maintenable et évolutif tout en respectant une séparation claire entre la présentation, la logique métier et la persistance.
