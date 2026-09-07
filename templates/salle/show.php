<?php

$title = 'Détail de la salle';

ob_start();
?>

<h2>Détail de la salle</h2>

<p>
    <strong>ID :</strong>
    <?= htmlspecialchars((string) $salle->id) ?>
</p>

<p>
    <strong>Nom :</strong>
    <?= htmlspecialchars($salle->nom) ?>
</p>

<p>
    <strong>Bâtiment :</strong>
    <?= htmlspecialchars($salle->batiment) ?>
</p>

<p>
    <strong>Capacité :</strong>
    <?= htmlspecialchars((string) $salle->capacite) ?>
</p>

<p>
    <strong>Type :</strong>
    <?= htmlspecialchars($salle->type) ?>
</p>

<p>
    <strong>Statut :</strong>
    <?= $salle->active ? 'Active' : 'Inactive' ?>
</p>

<p>
    <a href="/salles">Retour à la liste</a>
</p>

<p>
    <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit">
        Modifier cette salle
    </a>
</p>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
