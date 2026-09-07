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

L'application utilise une architecture organisée en plusieurs couches afin de séparer les responsabilités.

---

# 2. Architecture globale

L'architecture générale du projet peut être représentée ainsi :

```text
                         Navigateur
                             │
                             ▼
                    public/index.php
                     Front Controller
                             │
                             ▼
                    FastRoute / Router
                             │
                             ▼
                       Controller
                    ┌────────┴────────┐
                    │                 │
                    ▼                 ▼
                Validator           Service
                                      │
                                      ▼
                                  Repository
                                      │
                                      ▼
                                    Model
                                      │
                                      ▼
                                   Eloquent
                                      │
                                      ▼
                                    MySQL
```

Les vues sont utilisées par les contrôleurs pour produire les pages HTML :

```text
Controller
    │
    ├──────────────► View / Template
    │
    └──────────────► Service
```

L'objectif principal est d'éviter qu'une seule classe fasse tout le travail.

---

# 3. Organisation du projet

L'application suit principalement cette organisation :

```text
reservation-salles/
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

Le modèle MVC sépare l'application en trois responsabilités principales.

### Model

Le modèle représente les données et leur interaction avec la base de données.

Dans notre projet :

```text
src/Model/
├── Salle.php
└── Reservation.php
```

### View

La vue est responsable de l'affichage HTML.

Dans notre projet :

```text
templates/
├── salle/
└── reservation/
```

### Controller

Le contrôleur reçoit la requête et coordonne les différentes couches.

Dans notre projet :

```text
src/Controller/
├── SalleController.php
└── ReservationController.php
```

---

## 4.2 Flux MVC

Pour une création de réservation :

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
ReservationController
     │
     ▼
ReservationValidator
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
MySQL
```

---

## 4.3 Avantage

MVC permet de séparer :

* la présentation ;
* la logique HTTP ;
* les données.

Cela rend le projet plus facile à maintenir et à faire évoluer.

## 4.4 Limite

MVC ne suffit pas à organiser toute l'application.

Un gros contrôleur pourrait toujours contenir :

* validation ;
* logique métier ;
* requêtes SQL ;
* affichage.

C'est pourquoi notre architecture ajoute d'autres couches :

```text
Validator
DTO
Repository
Service
DI Container
```

---

# 5. Front Controller

## 5.1 Définition

Le **Front Controller** est un point d'entrée unique pour les requêtes HTTP.

Dans notre application :

```text
public/index.php
```

est le Front Controller.

Toutes les requêtes passent par lui.

---

## 5.2 Exemple

Une requête :

```text
GET /salles
```

arrive dans :

```text
public/index.php
```

Le fichier initialise ensuite :

* Composer ;
* Eloquent ;
* le conteneur DI ;
* le routeur.

Puis il demande au routeur quelle action doit être exécutée.

---

## 5.3 Extrait représentatif

```php
$dispatcher = simpleDispatcher(
    require __DIR__ . '/../routes/web.php'
);

$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
```

---

## 5.4 Avantages

Le Front Controller permet :

* d'avoir un point d'entrée unique ;
* de centraliser le routage ;
* de centraliser l'initialisation de l'application ;
* d'éviter plusieurs fichiers PHP publics servant directement de contrôleurs.

---

# 6. Router

## 6.1 Définition

Le routeur détermine quelle action doit être exécutée pour une URL donnée.

Nous utilisons :

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
      │
      ▼
SalleController::index()
```

---

## 6.3 Routes dynamiques

Nous avons également des routes dynamiques :

```php
$r->addRoute(
    'GET',
    '/salles/{id:\d+}',
    [SalleController::class, 'show']
);
```

Par exemple :

```text
GET /salles/2
```

appelle :

```php
SalleController::show(2);
```

---

## 6.4 Gestion des erreurs HTTP

Le routeur gère également :

```text
404 Not Found
```

et :

```text
405 Method Not Allowed
```

Pour une méthode interdite, l'application fournit également :

```http
Allow: GET, POST
```

---

## 6.5 Avantage

Le routage est séparé du contrôleur.

Les URLs ne sont donc pas codées directement dans chaque contrôleur.

---

# 7. Validator

## 7.1 Définition

Le Validator vérifie que les données reçues respectent les règles de validation avant leur utilisation.

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

## 7.2 Exemple

Dans `ReservationController` :

```php
$data = $_POST;

$result = $this->reservationValidator->validate($data);

if (!$result->isValid()) {
    $errors = $result->errors();

    require __DIR__ . '/../../templates/reservation/form.php';

    return;
}
```

Le contrôleur ne fait donc pas lui-même toutes les règles de validation.

---

## 7.3 Validation d'une réservation

Les données contrôlées comprennent notamment :

* `salle_id` ;
* `responsable` ;
* `email` ;
* `motif` ;
* `date_debut` ;
* `date_fin`.

La validation utilise :

```text
respect/validation
```

---

## 7.4 Validation HTTP et logique métier

Il est important de distinguer deux niveaux.

### Validation des données

Exemple :

```text
email valide ?
motif suffisamment long ?
date correctement formatée ?
```

Cette responsabilité appartient au Validator.

### Validation métier

Exemple :

```text
la salle existe-t-elle ?
la salle est-elle active ?
la réservation chevauche-t-elle une autre réservation ?
la durée dépasse-t-elle quatre heures ?
```

Cette responsabilité appartient au Service.

Cette séparation est essentielle.

---

# 8. DTO

## 8.1 Définition

DTO signifie :

```text
Data Transfer Object
```

Un DTO est un objet utilisé pour transporter des données structurées entre différentes couches de l'application.

---

## 8.2 DTO de salle

Nous avons :

```text
App\DTO\CreerSalleDTO
```

Il contient :

```php
public readonly string $nom;
public readonly string $batiment;
public readonly int $capacite;
public readonly string $type;
public readonly bool $active;
```

---

## 8.3 DTO de réservation

Nous avons :

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

## 8.4 Pourquoi utiliser un DTO ?

Le DTO permet de transformer les données HTTP :

```text
$_POST
```

en données structurées :

```text
CreerReservationDTO
```

Le service reçoit ainsi un objet clair et typé.

---

## 8.5 Avantage

Le Service ne dépend pas directement de `$_POST`.

Il reçoit :

```php
CreerReservationDTO
```

au lieu de :

```php
$_POST
```

Cela améliore :

* le typage ;
* la lisibilité ;
* les tests ;
* la séparation des responsabilités.

---

# 9. ORM

## 9.1 Définition

ORM signifie :

```text
Object-Relational Mapping
```

Un ORM permet de représenter les données relationnelles sous forme d'objets PHP.

Notre ORM est :

```text
Eloquent
```

fourni par :

```text
illuminate/database
```

---

## 9.2 Sans ORM

Sans ORM, on pourrait écrire :

```php
SELECT * FROM salles WHERE id = ?
```

puis transformer manuellement le résultat en objet.

---

## 9.3 Avec Eloquent

Nous pouvons écrire :

```php
$salle = Salle::find($id);
```

Eloquent s'occupe de la communication avec la base de données.

---

## 9.4 Avantage

L'ORM permet de travailler principalement avec des objets PHP :

```text
Salle
Reservation
```

plutôt qu'avec des tableaux de résultats SQL.

---

## 9.5 Limites

Un ORM peut :

* masquer la complexité SQL ;
* produire des requêtes inefficaces si mal utilisé ;
* encourager l'utilisation excessive de fonctionnalités magiques ;
* rendre certaines optimisations SQL plus complexes.

Il faut donc connaître SQL même lorsque l'on utilise Eloquent.

---

# 10. Active Record

## 10.1 Définition

Eloquent utilise principalement le pattern **Active Record**.

Un modèle représente à la fois :

* les données ;
* certaines opérations de persistance.

Par exemple :

```php
$salle = new Salle();

$salle->nom = 'Salle B20';

$salle->save();
```

L'objet `Salle` sait donc comment être enregistré par Eloquent.

---

## 10.2 Exemple dans notre projet

Dans `SalleRepository` :

```php
public function enregistrer(Salle $salle): Salle
{
    $salle->save();

    return $salle;
}
```

Le repository délègue ici la persistance à Eloquent.

---

## 10.3 Avantage

Active Record est :

* simple ;
* rapide à utiliser ;
* adapté aux applications CRUD ;
* bien intégré à Eloquent.

---

## 10.4 Limite

Le modèle possède une responsabilité supplémentaire : la persistance.

Cela peut être moins adapté à des domaines métier très complexes.

C'est notamment pour cela que notre architecture ajoute les Services et Repositories.

---

# 11. Repository

## 11.1 Définition

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

## 11.2 Exemple

```php
public function retrouver(int $id): ?Salle
{
    return Salle::find($id);
}
```

Le contrôleur n'a donc pas besoin de connaître la manière exacte dont la salle est récupérée.

---

## 11.3 Recherche de conflit

La recherche des conflits appartient au repository :

```php
return Reservation::query()
    ->where('salle_id', $salleId)
    ->where('statut', 'confirmée')
    ->where('date_debut', '<', $dateFin)
    ->where('date_fin', '>', $dateDebut)
    ->first();
```

Le Repository sait comment rechercher dans la base.

Le Service sait **pourquoi** cette recherche est nécessaire.

---

## 11.4 Avantage

Le Repository permet :

* d'isoler l'accès aux données ;
* de réduire les requêtes dans les contrôleurs ;
* de faciliter les tests ;
* de pouvoir faire évoluer la persistance.

---

## 11.5 Limite

Il faut éviter de transformer les repositories en classes contenant toute la logique métier.

Un repository doit principalement gérer l'accès aux données.

---

# 12. Service

## 12.1 Définition

Le Service contient la logique métier de l'application.

Notre couche :

```text
src/Service/
```

contient :

```text
CreerSalleService
CreerReservationService
AnnulerReservationService
```

---

# 13. Exemple : création d'une réservation

Le service :

```text
CreerReservationService
```

effectue plusieurs contrôles métier.

---

## 13.1 Vérification de la salle

```php
$salle = $this->salleRepository->retrouver($dto->salleId);

if ($salle === null || !$salle->active) {
    throw new SalleIndisponibleException(
        'La salle demandée est inexistante ou inactive.'
    );
}
```

---

## 13.2 Vérification des dates

```php
if ($dto->dateDebut >= $dto->dateFin) {
    throw new \InvalidArgumentException(
        'La date de début doit précéder la date de fin.'
    );
}
```

---

## 13.3 Vérification de la durée

```php
$duree = $dto->dateFin->getTimestamp()
    - $dto->dateDebut->getTimestamp();

if ($duree > 4 * 60 * 60) {
    throw new \InvalidArgumentException(
        'La durée de réservation ne peut pas dépasser quatre heures.'
    );
}
```

---

## 13.4 Vérification du futur

```php
if ($dto->dateDebut <= new \DateTimeImmutable()) {
    throw new \InvalidArgumentException(
        'La date de début doit être dans le futur.'
    );
}
```

---

## 13.5 Vérification du conflit

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

## 13.6 Principe important

Le Service contient donc les règles métier :

```text
Salle active
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

# 14. Injection de dépendances par constructeur

## 14.1 Définition

L'injection de dépendances consiste à fournir à une classe les objets dont elle a besoin au lieu de les créer elle-même.

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

Le service reçoit ses dépendances dans son constructeur.

---

## 14.2 Mauvaise approche

On pourrait faire :

```php
class CreerReservationService
{
    public function executer()
    {
        $repository = new ReservationRepository();
    }
}
```

Le service serait alors fortement couplé à l'implémentation concrète.

---

## 14.3 Notre approche

Nous utilisons :

```php
ReservationRepositoryInterface
```

et :

```php
SalleRepositoryInterface
```

Cela réduit le couplage.

---

# 15. Conteneur DI

## 15.1 Définition

DI signifie :

```text
Dependency Injection
```

Le conteneur DI est responsable de construire les objets et de résoudre leurs dépendances.

Notre application utilise :

```text
PHP-DI
```

---

## 15.2 Configuration

Le conteneur est configuré dans :

```text
config/container.php
```

Nous définissons notamment :

```php
SalleRepositoryInterface::class => function () {
    return new SalleRepository();
},
```

et :

```php
ReservationRepositoryInterface::class => function () {
    return new ReservationRepository();
},
```

---

## 15.3 Résolution d'un contrôleur

Dans le Front Controller :

```php
$controller = $container->get($controllerClass);
```

Le conteneur construit alors le contrôleur avec ses dépendances.

---

# 16. Autowiring

## 16.1 Définition

L'autowiring permet au conteneur de détecter automatiquement les dépendances d'une classe grâce à son constructeur et à ses types.

Par exemple :

```php
class AnnulerReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository
    ) {
    }
}
```

PHP-DI peut identifier la dépendance.

Cependant, une interface ne peut pas toujours être instanciée directement.

C'est pourquoi nous configurons explicitement les correspondances :

```text
ReservationRepositoryInterface
          ↓
ReservationRepository
```

et :

```text
SalleRepositoryInterface
          ↓
SalleRepository
```

---

# 17. IoC — Inversion of Control

## 17.1 Définition

IoC signifie :

```text
Inversion of Control
```

L'idée est que les classes ne contrôlent plus directement la création de toutes leurs dépendances.

Le contrôle est transféré au conteneur.

---

## 17.2 Sans IoC

```text
Controller
   │
   ├── new Repository()
   ├── new Service()
   └── new Validator()
```

Le contrôleur crée tout lui-même.

---

## 17.3 Avec IoC

```text
                Container
                    │
          ┌─────────┼─────────┐
          ▼         ▼         ▼
     Controller  Service  Repository
```

Le conteneur construit les objets.

---

## 17.4 Avantage

L'IoC permet de :

* réduire le couplage ;
* centraliser la construction des objets ;
* faciliter les tests ;
* remplacer plus facilement une implémentation.

---

# 18. SOLID

SOLID regroupe cinq principes de conception orientée objet.

```text
S = Single Responsibility Principle
O = Open/Closed Principle
L = Liskov Substitution Principle
I = Interface Segregation Principle
D = Dependency Inversion Principle
```

---

# 19. S — Single Responsibility Principle

## Principe

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

Chaque classe a donc un rôle précis.

---

# 20. O — Open/Closed Principle

## Principe

Une classe doit être ouverte à l'extension mais fermée à la modification.

Nos interfaces permettent notamment d'ajouter une autre implémentation.

Exemple :

```php
interface SalleRepositoryInterface
{
    public function lister(): array;

    public function retrouver(int $id): ?Salle;

    public function enregistrer(Salle $salle): Salle;
}
```

On pourrait créer une autre implémentation du repository sans modifier le contrat.

---

# 21. L — Liskov Substitution Principle

## Principe

Une implémentation doit pouvoir remplacer son abstraction sans casser le fonctionnement attendu.

Dans notre projet :

```text
SalleRepositoryInterface
          ▲
          │
SalleRepository
```

Le service travaille avec :

```php
SalleRepositoryInterface
```

et non directement avec :

```php
SalleRepository
```

L'implémentation respecte donc le contrat défini par l'interface.

---

# 22. I — Interface Segregation Principle

## Principe

Il vaut mieux avoir plusieurs interfaces spécialisées qu'une énorme interface contenant des méthodes inutiles.

Dans notre architecture, nous séparons notamment :

```text
SalleRepositoryInterface
```

et :

```text
ReservationRepositoryInterface
```

Une classe qui travaille avec les salles n'est donc pas obligée de dépendre des méthodes relatives aux réservations.

---

# 23. D — Dependency Inversion Principle

## Principe

Les classes de haut niveau doivent dépendre d'abstractions plutôt que d'implémentations concrètes.

Notre Service utilise :

```php
ReservationRepositoryInterface
```

plutôt que :

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

C'est l'un des principes les plus importants de notre architecture.

---

# 24. Flux complet d'une réservation

Voici le fonctionnement complet lorsqu'un utilisateur crée une réservation.

## Étape 1 — Requête HTTP

L'utilisateur envoie :

```text
POST /reservations
```

---

## Étape 2 — Front Controller

La requête arrive dans :

```text
public/index.php
```

---

## Étape 3 — Router

FastRoute trouve :

```php
[ReservationController::class, 'store']
```

---

## Étape 4 — Conteneur DI

Le conteneur PHP-DI récupère :

```text
ReservationController
```

avec ses dépendances.

---

## Étape 5 — Controller

Le contrôleur récupère :

```php
$_POST
```

---

## Étape 6 — Validator

Les données sont validées :

```text
ReservationValidator
```

---

## Étape 7 — DTO

Les données validées sont transformées en :

```text
CreerReservationDTO
```

Les dates deviennent notamment :

```text
DateTimeImmutable
```

---

## Étape 8 — Service

Le DTO est transmis :

```php
$this->creerReservationService->executer($dto);
```

Le service vérifie les règles métier.

---

## Étape 9 — Repository

Le service demande au repository :

```php
$this->reservationRepository->rechercherConflit(...)
```

---

## Étape 10 — Model / Eloquent

Le repository utilise :

```text
Reservation
```

qui s'appuie sur Eloquent.

---

## Étape 11 — MySQL

Eloquent exécute la requête auprès de MySQL.

---

## Étape 12 — Création

Si aucune règle métier n'est violée :

```text
Reservation
     ↓
save()
     ↓
MySQL
```

---

## Étape 13 — Redirection

Le contrôleur redirige vers :

```text
/reservations
```

---

# 25. Exemple de flux en une seule vue

```text
┌───────────────────────┐
│      Navigateur       │
└───────────┬───────────┘
            │ POST /reservations
            ▼
┌───────────────────────┐
│   public/index.php    │
│   Front Controller    │
└───────────┬───────────┘
            ▼
┌───────────────────────┐
│       FastRoute       │
│        Router         │
└───────────┬───────────┘
            ▼
┌───────────────────────┐
│ ReservationController │
└───────────┬───────────┘
            ▼
┌───────────────────────┐
│ ReservationValidator  │
└───────────┬───────────┘
            │ données valides
            ▼
┌───────────────────────┐
│ CreerReservationDTO   │
└───────────┬───────────┘
            ▼
┌──────────────────────────┐
│ CreerReservationService  │
└────────────┬─────────────┘
             ▼
┌──────────────────────────┐
│ ReservationRepository    │
└────────────┬─────────────┘
             ▼
┌──────────────────────────┐
│ Reservation / Eloquent   │
└────────────┬─────────────┘
             ▼
┌──────────────────────────┐
│          MySQL           │
└──────────────────────────┘
```

---

# 26. Séparation des responsabilités

L'architecture respecte les règles suivantes.

## Controller

Le Controller :

* reçoit la requête ;
* appelle le Validator ;
* construit le DTO ;
* appelle le Service ;
* prépare les données pour la vue ;
* effectue les redirections HTTP.

Le Controller ne doit pas contenir les règles métier complexes.

---

## Validator

Le Validator :

* vérifie les données entrantes ;
* retourne les erreurs de validation.

Il ne doit pas créer directement une réservation.

---

## DTO

Le DTO :

* transporte les données ;
* fournit une structure claire et typée.

Il ne doit pas contenir la logique métier.

---

## Service

Le Service :

* applique les règles métier ;
* coordonne les repositories ;
* décide si une opération métier est autorisée.

Il ne doit pas gérer directement l'affichage HTML.

---

## Repository

Le Repository :

* récupère les données ;
* enregistre les données ;
* recherche les conflits ;
* annule une réservation.

Il ne doit pas gérer l'affichage ou la requête HTTP.

---

## Model

Le Model représente les données et leur interaction avec Eloquent.

---

## View

La View affiche les données.

Elle ne doit pas contenir de règles métier.

---

# 27. Règles métier centralisées

La création d'une réservation respecte les règles suivantes :

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

Une réservation annulée ne bloque donc plus la salle.

---

# 28. Gestion des exceptions

Le projet possède des exceptions métier spécifiques :

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

Cela permet de représenter clairement les erreurs métier.

La gestion globale de ces exceptions pourra être améliorée afin de transformer proprement les exceptions métier en réponses HTTP adaptées.

---

# 29. Sécurité et séparation des configurations

Les informations sensibles de connexion à la base de données sont stockées dans :

```text
.env
```

et non directement dans le code source.

Le projet fournit :

```text
.env.example
```

comme modèle de configuration.

Les secrets ne doivent pas être versionnés dans Git.

---

# 30. Dépendances principales

Le projet utilise notamment :

| Dépendance            | Rôle                      |
| --------------------- | ------------------------- |
| `nikic/fast-route`    | Routage HTTP              |
| `respect/validation`  | Validation des données    |
| `illuminate/database` | Eloquent ORM              |
| `php-di/php-di`       | Injection de dépendances  |
| `vlucas/phpdotenv`    | Variables d'environnement |
| `phpunit/phpunit`     | Tests                     |

---

# 31. Pourquoi cette architecture ?

Cette architecture évite d'avoir une application construite autour d'un seul fichier contenant :

```text
SQL
+
HTML
+
$_POST
+
validation
+
logique métier
+
redirections
```

Au contraire, les responsabilités sont réparties :

```text
HTTP
 ↓
Controller

Validation
 ↓
Validator

Transport
 ↓
DTO

Métier
 ↓
Service

Données
 ↓
Repository

Persistance
 ↓
Model / Eloquent

Base
 ↓
MySQL
```

Cette organisation rend le projet :

* plus lisible ;
* plus maintenable ;
* plus testable ;
* plus évolutif ;
* moins fortement couplé.

---

# 32. Avantages de l'architecture

### Lisibilité

Chaque couche possède un rôle identifiable.

### Maintenabilité

Une modification peut être localisée dans la couche concernée.

### Testabilité

Les services et repositories peuvent être testés séparément.

### Réutilisabilité

Une règle métier située dans un Service peut être appelée depuis plusieurs contrôleurs.

### Faible couplage

Les interfaces et l'injection de dépendances limitent les dépendances directes aux implémentations.

### Évolutivité

L'application peut évoluer progressivement sans transformer le contrôleur en classe gigantesque.

---

# 33. Limites et risques

Cette architecture apporte davantage de structure, mais également davantage de classes.

Pour une petite application, on pourrait considérer que :

```text
Controller
+
Service
+
Repository
+
DTO
+
Validator
+
Model
```

représente beaucoup de couches.

Cependant, dans le contexte de ce projet, cette organisation est volontaire : elle permet de pratiquer plusieurs concepts d'architecture logicielle et de respecter les contraintes du projet.

Il faut également éviter :

* des services trop gros ;
* des repositories contenant la logique métier ;
* des contrôleurs contenant trop de logique ;
* des modèles surchargés ;
* des interfaces créées sans véritable besoin ;
* une utilisation excessive du conteneur DI.

---

# 34. Résumé des responsabilités

| Élément            | Responsabilité principale                 |
| ------------------ | ----------------------------------------- |
| `public/index.php` | Point d'entrée unique                     |
| FastRoute          | Routage HTTP                              |
| Controller         | Coordination HTTP                         |
| Validator          | Validation des données                    |
| DTO                | Transport des données                     |
| Service            | Logique métier                            |
| Repository         | Accès aux données                         |
| Model              | Représentation des données                |
| Eloquent           | Persistance ORM                           |
| PHP-DI             | Construction et injection des dépendances |
| View               | Affichage HTML                            |
| MySQL              | Stockage des données                      |

---

# 35. Architecture finale

L'architecture retenue peut être résumée ainsi :

```text
                           ┌───────────────┐
                           │   Navigateur  │
                           └───────┬───────┘
                                   │
                                   ▼
                           ┌───────────────┐
                           │    Front      │
                           │   Controller  │
                           │ index.php     │
                           └───────┬───────┘
                                   │
                                   ▼
                           ┌───────────────┐
                           │    Router     │
                           │   FastRoute   │
                           └───────┬───────┘
                                   │
                                   ▼
                       ┌───────────────────────┐
                       │      Controller       │
                       └───────────┬───────────┘
                                   │
                    ┌──────────────┴──────────────┐
                    │                             │
                    ▼                             ▼
             ┌─────────────┐              ┌─────────────┐
             │  Validator  │              │    View     │
             └──────┬──────┘              └─────────────┘
                    │
                    ▼
             ┌─────────────┐
             │     DTO     │
             └──────┬──────┘
                    │
                    ▼
             ┌─────────────┐
             │   Service   │
             │ Métier      │
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
             │    MySQL    │
             └─────────────┘
```

---

# 36. Conclusion

L'application **Gestion University** utilise une architecture orientée objet structurée autour de plusieurs responsabilités.

Le parcours principal d'une requête est :

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
Model / Eloquent
 ↓
MySQL
```

Cette architecture permet de séparer clairement :

* la réception des requêtes ;
* le routage ;
* la validation ;
* le transport des données ;
* la logique métier ;
* l'accès aux données ;
* la persistance ;
* la présentation.

Elle met également en pratique plusieurs concepts fondamentaux de conception logicielle :

```text
MVC
Front Controller
Router
Validator
DTO
ORM
Active Record
Repository
Service
Dependency Injection
DI Container
Autowiring
IoC
SOLID
```

L'objectif n'est donc pas uniquement de faire fonctionner l'application, mais de construire une application dont les responsabilités sont clairement séparées et dont le code peut évoluer sans créer un couplage excessif entre les différentes parties.
