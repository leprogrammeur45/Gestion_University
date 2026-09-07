<?php

$title = 'Liste des salles';

ob_start();
?>

<h2>Liste des salles</h2>

<a href="/salles/create">Ajouter une salle</a>

<?php if (empty($salles)): ?>

    <p>Aucune salle disponible.</p>

<?php else: ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Bâtiment</th>
                <th>Capacité</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($salles as $salle): ?>

            <tr>
                <td><?= htmlspecialchars((string) $salle->id) ?></td>

                <td>
                    <?= htmlspecialchars($salle->nom) ?>
                </td>

                <td>
                    <?= htmlspecialchars($salle->batiment) ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $salle->capacite) ?>
                </td>

                <td>
                    <?= htmlspecialchars($salle->type) ?>
                </td>

                <td>
                    <?= $salle->active ? 'Active' : 'Inactive' ?>
                </td>

                <td>
                    <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>">
                        Voir
                    </a>

                    <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit">
                        Modifier
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>

<?php
$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
