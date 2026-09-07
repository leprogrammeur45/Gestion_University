<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title ?? 'Gestion des salles') ?></title>

    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>

<header>
    <h1>Gestion des salles</h1>

    <nav>
        <a href="/salles">Salles</a>
        <a href="/reservations">Réservations</a>
    </nav>
</header>

<main>
    <?= $content ?? '' ?>
</main>

</body>
</html>
