<?php

$title = 'Détail de la salle';

ob_start();

?>

<div class="page-header">

    <div>

        <h2>
            🏫 <?= htmlspecialchars($salle->nom) ?>
        </h2>

        <p>
            Consultez les informations de cette salle.
        </p>

    </div>

    <?php if ($salle->active): ?>

        <span class="badge badge-success">
            <span class="badge-dot"></span>
            Active
        </span>

    <?php else: ?>

        <span class="badge badge-neutral">
            <span class="badge-dot"></span>
            Inactive
        </span>

    <?php endif; ?>

</div>


<div class="detail-card">

    <div class="detail-card-header">

        <div class="detail-card-icon">
            🏫
        </div>

        <div>

            <h3>
                Informations de la salle
            </h3>

            <p>
                Détails concernant cette salle universitaire.
            </p>

        </div>

    </div>


    <div class="detail-row">

        <span class="label">
            Nom
        </span>

        <span class="value">
            <?= htmlspecialchars($salle->nom) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Bâtiment
        </span>

        <span class="value">
            <?= htmlspecialchars($salle->batiment) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Capacité
        </span>

        <span class="value">
            <?= htmlspecialchars((string) $salle->capacite) ?>
            personnes
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Type
        </span>

        <span class="value">
            <span class="type-badge">
                <?= htmlspecialchars($salle->type) ?>
            </span>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Statut
        </span>

        <span class="value">

            <?php if ($salle->active): ?>

                <span class="badge badge-success">

                    <span class="badge-dot"></span>

                    Active

                </span>

            <?php else: ?>

                <span class="badge badge-neutral">

                    <span class="badge-dot"></span>

                    Inactive

                </span>

            <?php endif; ?>

        </span>

    </div>

</div>


<div class="actions-row">

    <a
        href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit"
        class="btn btn-primary"
    >
        ✏️ Modifier
    </a>

    <a
        href="/salles"
        class="btn btn-secondary"
    >
        ← Retour aux salles
    </a>

</div>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
?>

