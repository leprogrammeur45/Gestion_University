<?php

$title = 'Liste des salles';

ob_start();

?>

<div class="page-header">

    <div>

        <h2>🏫 Salles</h2>

        <p>
            Consultez et gérez les salles de l'université.
        </p>

    </div>

    <a
        href="/salles/create"
        class="btn btn-primary"
    >
        + Ajouter une salle
    </a>

</div>

<?php if (($_GET['success'] ?? '') === 'salle_created'): ?>
    <div class="alert alert-success">La salle a été ajoutée avec succès.</div>
<?php elseif (($_GET['success'] ?? '') === 'salle_updated'): ?>
    <div class="alert alert-success">La salle a été modifiée avec succès.</div>
<?php endif; ?>


<?php if (empty($salles)): ?>

    <div class="empty-state">

        <div class="empty-state-icon">
            🏫
        </div>

        <h3>
            Aucune salle
        </h3>

        <p>
            Aucune salle n'est disponible pour le moment.
        </p>

        <a
            href="/salles/create"
            class="btn btn-primary"
        >
            + Ajouter une salle
        </a>

    </div>

<?php else: ?>

    <div class="card">

        <div class="table-header">

            <div>

                <h3>
                    Toutes les salles
                </h3>

                <p>
                    Liste des salles enregistrées dans l'université.
                </p>

            </div>

            <span class="table-count">

                <?= count($salles) ?>

                salle<?= count($salles) > 1 ? 's' : '' ?>

            </span>

        </div>


        <div class="table-wrapper">

            <table class="data-table">

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

                        <td data-label="ID">

                            <span class="room-reference">
                                #<?= htmlspecialchars((string) $salle->id) ?>
                            </span>

                        </td>


                        <td data-label="Nom">

                            <strong>
                                <?= htmlspecialchars($salle->nom) ?>
                            </strong>

                        </td>


                        <td data-label="Bâtiment">

                            <?= htmlspecialchars($salle->batiment) ?>

                        </td>


                        <td data-label="Capacité">

                            <span class="capacity-value">
                                <?= htmlspecialchars((string) $salle->capacite) ?>
                                places
                            </span>

                        </td>


                        <td data-label="Type">

                            <span class="type-badge">
                                <?= htmlspecialchars($salle->type) ?>
                            </span>

                        </td>


                        <td data-label="Statut">

                            <?php if ($salle->active): ?>

                                <span class="badge badge-success">

                                    <span class="badge-dot"></span>

                                    Active

                                </span>

                            <?php else: ?>

                                <span class="badge badge-danger">

                                    <span class="badge-dot"></span>

                                    Inactive

                                </span>

                            <?php endif; ?>

                        </td>


                        <td data-label="Actions">

                            <div class="table-actions">

                                <a
                                    href="/salles/<?= htmlspecialchars((string) $salle->id) ?>"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Voir
                                </a>

                                <a
                                    href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit"
                                    class="btn btn-primary btn-sm"
                                >
                                    Modifier
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

<?php endif; ?>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
?>
